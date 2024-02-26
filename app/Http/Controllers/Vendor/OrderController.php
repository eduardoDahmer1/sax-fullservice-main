<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Order;
use App\Models\VendorOrder;
use Yajra\DataTables\DataTables;
use App\Models\Currency;
use App\Models\User;
use App\Models\Generalsetting;
use App\Classes\GeniusMailer;
use App\Models\OrderTrack;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();
            if ($user->checkWarning()) {
                return redirect()->route('vendor-warning', $user->verifies()->where('admin_warning', '=', '1')->orderBy('id', 'desc')->first()->id);
            }
            if (!$user->checkStatus()) {
                return redirect()->route('vendor-verify');
            }
            return $next($request);
        });
    }

    public function datatables()
    {
        $user = Auth::user();
        $datas = VendorOrder::where('user_id', $user->id)->orderBy('id', 'desc');
        return Datatables::of($datas)
        ->editColumn('customer_email', function (VendorOrder $data) {
            $customer_email = $data->order->customer_email;
            return $customer_email;
        })
        ->editColumn('totalQty', function (VendorOrder $data) {
            $totalQty = $data->order->totalQty;
            return $totalQty;
        })
        ->editColumn('customer_name', function (VendorOrder $data) {
            $customer_name = $data->order->customer_name;
            return $customer_name;
        })
        ->editColumn('method', function (VendorOrder $data) {
            $method = __($data->order->method);
            $type = ($method == __("Simplified")) ? "success" : "info";
            return '<span class="badge badge-'.$type.'">'.__(ucwords($method)).'</span>';
        })
        ->editColumn('customer_phone', function (VendorOrder $data) {
            $customer_phone = $data->order->customer_phone;
            return $customer_phone;
        })
        ->editColumn('order_number', function (VendorOrder $data) {
            $order_number = '<a href="' . route('vendor-order-invoice', $data->order_id) . '">' . $data->order_number . '</a>';
            return $order_number;
        })
        ->editColumn('pay_amount', function (VendorOrder $data) {
            $first_curr = Currency::where('id', '=', 1)->first();
            $order_curr = Currency::where('sign', '=', $data->order->currency_sign)->first();
            if (empty($order_curr)) {
                $order_curr = $first_curr;
            }
            return $data->order->currency_sign . number_format($data->order->pay_amount * $data->order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator);
        })
        ->editColumn('status', function (VendorOrder $data) {
            switch($data->order->status) {
                case "pending":
                    $type = "secondary";
                    $status = "Pending";
                    break;
                case "processing":
                    $type = "primary";
                    $status = "Processing";
                    break;
                case "on delivery":
                    $type = "warning";
                    $status = "On Delivery";
                    break;
                case "completed":
                    $type = "success";
                    $status = "Completed";
                    break;
                case "declined":
                    $type = "danger";
                    $status = "Declined";
                    break;
            }
            return '<span class="badge badge-'.$type.'">'.__($status).'</span>';
        })
        ->editColumn('payment_status', function (VendorOrder $data) {
            $type = $data->order->payment_status != 'Pending' ? "success" : "danger";
            $status = $data->order->payment_status != 'Pending' ? "Paid" : "Unpaid";
            return '<span class="badge badge-'.$type.'">'.__(ucfirst($status)).'</span>';
        })
        ->addColumn('action', function (VendorOrder $data) {
            $orders = '<a href="javascript:;" data-href="' . route('vendor-order-edit', $data->order_id) . '" data-header="' . __('Delivery Status') . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> ' . __('Delivery Status') . '</a>';
            return '<div class="godropdown"><button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('vendor-order-show', $data->order_id) . '" > <i class="fas fa-eye"></i> ' . __('Details') . '</a>'. $orders . '</div></div>';
        })
       ->rawColumns(['customer_email', 'customer_name', 'customer_phone', 'status', 'pay_amount', 'method', 'order_number', 'payment_status', 'action'])
        ->toJson();
    }

    public function index()
    {
        $user = Auth::user();
        $orders = VendorOrder::where('user_id', '=', $user->id)->orderBy('id', 'desc')->get()->groupBy('order_number');
        return view('vendor.order.index', compact('user', 'orders'));
    }

    public function edit($id)
    {
        $data = Order::find($id);
        return view('vendor.order.delivery', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = Order::findOrFail($id);

        $input = $request->all();
        if ($data->status == "completed") {
            // Then Save Without Changing it.
            $input['status'] = "completed";
            $data->update($input);
            //--- Logic Section Ends


            //--- Redirect Section
            $msg = __('Status Updated Successfully.');
            return response()->json($msg);
        //--- Redirect Section Ends
        } else {
            // processing -- em andamento
            if ($input['status'] == "processing") {
                foreach ($data->vendororders as $vorder) {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }

                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => __('Your order') . ' ' . $data->order_number . ' ' . __('is processing!'),
                        'body' => $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('Your order is now processing and will be soon in delivery.'),
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                } else {
                    $to = $data->customer_email;
                    $subject = __('Your order') . ' ' . $data->order_number . ' ' . __('is processing!');
                    $msg = $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('Your order is now processing and will be soon in delivery.');
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }


            //on delivery
            if ($input['status'] == "on delivery") {
                foreach ($data->vendororders as $vorder) {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }

                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => __('Your order') . ' ' . $data->order_number . ' ' . __('is on delivery!'),
                        'body' => $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('Your order is now on delivery and will be soon with you.'),
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                } else {
                    $to = $data->customer_email;
                    $subject = __('Your order') . ' ' . $data->order_number . ' ' . __('is on delivery!');
                    $msg = $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('Your order is now on delivery and will be soon with you.');
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }



            if ($input['status'] == "completed") {
                foreach ($data->vendororders as $vorder) {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }

                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => __('Your order') . ' ' . $data->order_number . ' ' . __('is Confirmed!'),
                        'body' => $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('Thank you for shopping with us. We are looking forward to your next visit.'),
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                } else {
                    $to = $data->customer_email;
                    $subject = __('Your order') . ' ' . $data->order_number . ' ' . __('is Confirmed!');
                    $msg = $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('Thank you for shopping with us. We are looking forward to your next visit.');
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }
            if ($input['status'] == "declined") {
                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => __('Your order') . ' ' . $data->order_number . ' ' . __('is Declined!'),
                        'body' => $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('We are sorry for the inconvenience caused. We are looking forward to your next visit.'),
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                } else {
                    $to = $data->customer_email;
                    $subject = __('Your order') . ' ' . $data->order_number . ' ' . __('is Declined!');
                    $msg = $this->storeSettings->title . "\n" .__('Hello') . ' ' . $data->customer_name . "," . "\n " . __('We are sorry for the inconvenience caused. We are looking forward to your next visit.');
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }


            $data->update($input);
            $title = __(ucwords($request->status));
            $order_track = new OrderTrack;

            $info = [
                "order_id" => $id,
                "{$this->lang->locale}" => [
                    "title" => $title,
                    "text" => (!empty($input[$this->lang->locale]['track_text'])) ? $input[$this->lang->locale]['track_text'] : ""
                ]
            ];

            foreach ($this->locales as $loc) {
                if ($loc->locale === $this->lang->locale) {
                    continue;
                }
                $info[$loc->locale]['title'] = $title;
                $info[$loc->locale]['text'] = $input[$loc->locale]['track_text'];
            }
            $order_track->fill($info)->save();


            $order = VendorOrder::where('order_id', '=', $id)->update(['status' => $input['status']]);

            //--- Redirect Section
            $msg = __('Status Updated Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }

        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function show($id)
    {
        $user = Auth::user();
        $order = Order::where('id', '=', $id)->first();
        $cart = $order->cart;
        return view('vendor.order.details', compact('user', 'order', 'cart'));
    }

    public function license(Request $request, $slug)
    {
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = $order->cart;
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = $cart;
        $order->update();
        $msg = __('Successfully Changed The License Key.');
        return response()->json($msg);
    }



    public function invoice($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = $order->cart;
        return view('vendor.order.invoice', compact('user', 'order', 'cart'));
    }

    public function printpage($slug)
    {
        $user = Auth::user();
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = $order->cart;
        return view('vendor.order.print', compact('user', 'order', 'cart'));
    }

    public function status($slug, $status)
    {
        $mainorder = VendorOrder::where('order_number', '=', $slug)->first();
        if ($mainorder->status == "completed") {
            return redirect()->back()->with('success', __('This Order is Already Completed'));
        } else {
            $user = Auth::user();
            $order = VendorOrder::where('order_number', '=', $slug)->where('user_id', '=', $user->id)->update(['status' => $status]);
            return redirect()->route('vendor-order-index')->with('success', __('Order Status Updated Successfully'));
        }
    }
}
