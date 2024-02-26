<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\CategoryGallery;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\User;
use App\Models\VendorOrder;
use Yajra\DataTables\DataTables;
use App\Models\Currency;
use App\Classes\MelhorEnvio;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Seotool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables($status)
    {
        if ($status == 'pending') {
            $datas = Order::where([
                ['status', '=', 'pending'],
                ['method', '<>', 'Simplified']
            ]);
        } elseif ($status == 'processing') {
            $datas = Order::where([
                ['status', '=', 'processing'],
                ['method', '<>', 'Simplified']
            ]);
        } elseif ($status == 'delivery') {
            $datas = Order::where([
                ['status', '=', 'on delivery'],
                ['method', '<>', 'Simplified']
            ]);
        } elseif ($status == 'completed') {
            $datas = Order::where([
                ['status', '=', 'completed'],
                ['method', '<>', 'Simplified']
            ]);
        } elseif ($status == 'declined') {
            $datas = Order::where([
                ['status', '=', 'declined'],
                ['method', '<>', 'Simplified']
            ]);
        } else {
            $datas = Order::where('method', '<>', 'Simplified')->select('id', 'currency_sign', 'currency_value', 'order_number', 'pay_amount', 'status', 'method', 'payment_status', 'customer_email', 'customer_name', 'created_at', 'totalQty', 'shipping_cost', 'shipping_type', 'shipping_country', 'shipping_city', 'shipping_state', 'shipping_document');
        }
        $datas = $datas->orderBy('id', 'desc');

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('order_number', function (Order $data) {
                $order_number = '<a href="' . route('admin-order-invoice', $data->id) . '">' . $data->order_number . '</a>';
                return $order_number;
            })
            ->editColumn('pay_amount', function (Order $data) {
                $first_curr = Currency::where('id', '=', 1)->first();
                $order_curr = Currency::where('sign', '=', $data->currency_sign)->first();
                if (empty($order_curr)) {
                    $order_curr = $first_curr;
                }
                return $data->currency_sign . number_format($data->pay_amount * $order_curr->value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator);
            })
            ->editColumn('status', function (Order $data) {
                switch ($data->status) {
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

             ->editColumn('method', function (Order $data) {
                 $method = __($data->method);
                 $type = ($method == __("Simplified")) ? "success" : "info";
                 return '<span class="badge badge-'.$type.'">'.__(ucwords($method)).'</span>';
             })

            ->editColumn('payment_status', function (Order $data) {
                $type = $data->payment_status != 'Pending' ? "success" : "danger";
                $status = $data->payment_status != 'Pending' ? "Paid" : "Unpaid";
                return '<span class="badge badge-'.$type.'">'.__(ucfirst($status)).'</span>';
            })
            ->addColumn('action', function (Order $data) {
                $orders = '<a href="javascript:;" data-href="' . route('admin-order-edit', $data->id) . '" data-header="' . __('Delivery Status') . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> ' . __('Delivery Status') . '</a>';
                return '<div class="godropdown"><button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-order-show', $data->id) . '" > <i class="fas fa-eye"></i> ' . __('Details') . '</a><form method="POST"><a href="'. route('admin-order-send-order', $data->id) .'" class="send" data-email="' . $data->customer_email . '"><i class="fas fa-envelope"></i> ' . __('Send Order') . '</a></form><a href="javascript:;" data-href="' . route('admin-order-track', $data->id) . '" data-header="' . __('Track Order') . '" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> ' . __('Track Order') . '</a>' . $orders . '</div></div>';
            })
            ->rawColumns(['order_number', 'action', 'status', 'payment_status', 'method'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function datatablesSimplified($status)
    {
        if ($status == 'pending') {
            $datas = Order::where([
                ['status', '=', 'pending'],
                ['method', '=', 'Simplified']
            ]);
        } elseif ($status == 'processing') {
            $datas = Order::where([
                ['status', '=', 'processing'],
                ['method', '=', 'Simplified']
            ]);
        } elseif ($status == 'delivery') {
            $datas = Order::where([
                ['status', '=', 'on delivery'],
                ['method', '=', 'Simplified']
            ]);
        } elseif ($status == 'completed') {
            $datas = Order::where([
                ['status', '=', 'completed'],
                ['method', '=', 'Simplified']
            ]);
        } elseif ($status == 'declined') {
            $datas = Order::where([
                ['status', '=', 'declined'],
                ['method', '=', 'Simplified']
            ]);
        } else {
            $datas = Order::where('method', '=', 'Simplified')->orderBy('id', 'desc');
        }
        $datas = $datas->orderBy('id', 'desc');

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('customer_name', function (Order $data) {
                $customer_name = $data->customer_name;
                return $customer_name;
            })
            ->editColumn('customer_phone', function (Order $data) {
                $customer_phone = $data->customer_phone;
                return $customer_phone;
            })
            ->editColumn('order_number', function (Order $data) {
                $order_number = '<a href="' . route('admin-order-invoice', $data->id) . '">' . $data->order_number . '</a>';
                return $order_number;
            })
            ->editColumn('pay_amount', function (Order $data) {
                $first_curr = Currency::where('id', '=', 1)->first();
                $order_curr = Currency::where('sign', '=', $data->currency_sign)->first();
                if (empty($order_curr)) {
                    $order_curr = $first_curr;
                }
                return $data->currency_sign . number_format($data->pay_amount * $data->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator);
            })
            ->editColumn('status', function (Order $data) {
                switch ($data->status) {
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
             ->editColumn('payment_status', function (Order $data) {
                 $type = $data->payment_status != 'Pending' ? "success" : "danger";
                 $status = $data->payment_status != 'Pending' ? "Paid" : "Unpaid";
                 return '<span class="badge badge-'.$type.'">'.__(ucfirst($status)).'</span>';
             })
            ->addColumn('action', function (Order $data) {
                $orders = '<a href="javascript:;" data-href="' . route('admin-order-edit', $data->id) . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> ' . __('Delivery Status') . '</a>';
                return '<div class="godropdown"><button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-order-show', $data->id) . '" > <i class="fas fa-eye"></i> ' . __('Details') . '</a><a href="javascript:;" data-href="' . route('admin-order-track', $data->id) . '" data-header="' . __('Track Order') . '" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> ' . __('Track Order') . '</a>' . $orders . '</div></div>';
            })
            ->rawColumns(['customer_name', 'customer_phone' ,'order_number', 'action', 'status', 'payment_status'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function datatablesAll($status)
    {
        if ($status == 'pending') {
            $datas = Order::where('status', '=', 'pending');
        } elseif ($status == 'processing') {
            $datas = Order::where('status', '=', 'processing');
        } elseif ($status == 'delivery') {
            $datas = Order::where('status', '=', 'on delivery');
        } elseif ($status == 'completed') {
            $datas = Order::where('status', '=', 'completed');
        } elseif ($status == 'declined') {
            $datas = Order::where('status', '=', 'declined');
        }
        $datas = $datas->orderBy('id', 'desc');

        return Datatables::of($datas)
        ->editColumn('customer_name', function (Order $data) {
            $customer_name = $data->customer_name;
            return $customer_name;
        })
        ->editColumn('customer_phone', function (Order $data) {
            $customer_phone = $data->customer_phone;
            return $customer_phone;
        })
        ->editColumn('method', function (Order $data) {
            $method = __($data->method);
            $type = ($method == __("Simplified")) ? "success" : "info";
            return '<span class="badge badge-'.$type.'">'.__(ucwords($method)).'</span>';
        })
        ->editColumn('order_number', function (Order $data) {
            $order_number = '<a href="' . route('admin-order-invoice', $data->id) . '">' . $data->order_number . '</a>';
            return $order_number;
        })
        ->editColumn('pay_amount', function (Order $data) {
            $first_curr = Currency::where('id', '=', 1)->first();
            $order_curr = Currency::where('sign', '=', $data->currency_sign)->first();
            if (empty($order_curr)) {
                $order_curr = $first_curr;
            }
            return $data->currency_sign . number_format($data->pay_amount * $data->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator);
        })
        ->editColumn('status', function (Order $data) {
            switch ($data->status) {
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
         ->editColumn('payment_status', function (Order $data) {
             $type = $data->payment_status != 'Pending' ? "success" : "danger";
             $status = $data->payment_status != 'Pending' ? "Paid" : "Unpaid";
             return '<span class="badge badge-'.$type.'">'.__(ucfirst($status)).'</span>';
         })
         ->addColumn('action', function (Order $data) {
             $orders = '<a href="javascript:;" data-href="' . route('admin-order-edit', $data->id) . '" data-header="' . __('Delivery Status') . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> ' . __('Delivery Status') . '</a>';
             return '<div class="godropdown"><button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-order-show', $data->id) . '" > <i class="fas fa-eye"></i> ' . __('Details') . '</a><form method="POST"><a href="'. route('admin-order-send-order', $data->id) .'" class="send" data-email="' . $data->customer_email . '"><i class="fas fa-envelope"></i> ' . __('Send Order') . '</a></form><a href="javascript:;" data-href="' . route('admin-order-track', $data->id) . '" data-header="' . __('Track Order') . '" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> ' . __('Track Order') . '</a>' . $orders . '</div></div>';
         })
        ->rawColumns(['customer_name', 'customer_phone' ,'order_number', 'action', 'status', 'payment_status', 'method'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        $filters = [
            "all" => __('All Orders'),
            "pending" => __('Pending Orders'),
            "processing" => __('Processing Orders'),
            "delivery" => __('On Delivery Orders'),
            "completed" => __('Completed Orders'),
            "declined" => __('Declined Orders'),
        ];
        return view('admin.order.index', compact('filters'));
    }

    public function edit($id)
    {
        $data = Order::find($id);
        return view('admin.order.delivery', compact('data'));
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
                if ($data->status !== "declined") {
                    # Send declined order e-mail
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

                    # Rollback Products stock
                    $cart = new Cart($data->cart);
                    foreach ($cart->items as $item) {
                        $soldQuantity = $item['qty'];

                        $product = Product::find($item['item']['id']);
                        $product->stock += $soldQuantity;

                        if (isset($item['color_key']) && $product->color) {
                            if (!empty($item['color'])) {
                                $key = $item['color_key'];
                                $quantity = $item['qty'];
                                $color_qty = $product->color_qty;
                                $color_qty[$key] += (int)$quantity;
                                $color_qty = implode(',', $color_qty);
                                $product->color_qty = $color_qty;
                            }
                        }

                        if (isset($item['size_key'])) {
                            if (!empty($item['size'])) {
                                $key = $item['size_key'];
                                $quantity = $item['qty'];
                                $size_qty = $product->size_qty;
                                $size_qty[$key] += (int)$quantity;
                                $size_qty = implode(',', $size_qty);
                                $product->size_qty = $size_qty;
                            }
                        }

                        if (isset($item['material_key'])) {
                            if (!empty($item['material'])) {
                                $key = $item['material_key'];
                                $quantity = $item['qty'];
                                $material_qty = $product->material_qty;
                                $material_qty[$key] += (int)$quantity;
                                $material_qty = implode(',', $material_qty);
                                $product->material_qty = $material_qty;
                            }
                        }
                        $product->update();
                    }
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

    public function pending()
    {
        return view('admin.order.pending');
    }
    public function processing()
    {
        return view('admin.order.processing');
    }
    public function completed()
    {
        return view('admin.order.completed');
    }
    public function declined()
    {
        return view('admin.order.declined');
    }
    public function simplifiedCheckout()
    {
        $filters = [
            "all" => __('All Orders'),
            "pending" => __('Pending Orders'),
            "processing" => __('Processing Orders'),
            "delivery" => __('On Delivery Orders'),
            "completed" => __('Completed Orders'),
            "declined" => __('Declined Orders'),
        ];
        return view('admin.order.simplifiedCheckout', compact('filters'));
    }

    public function show($id)
    {
        if (!Order::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $order = Order::findOrFail($id);
        $cart = $order->cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();

        if (empty($order_curr)) {
            $order_curr = $first_curr;
        }

        $custom_galleries = CategoryGallery::all();

        return view('admin.order.details', compact('order', 'cart', 'first_curr', 'order_curr', 'custom_galleries'));
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);
        $cart = $order->cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
        if (empty($order_curr)) {
            $order_curr = $first_curr;
        }
        return view('admin.order.invoice', compact('order', 'cart', 'first_curr', 'order_curr'));
        }

    public function receipt($id)
    {
        $order = Order::findOrFail($id);
        $cart = $order->cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
        if (empty($order_curr)) {
            $order_curr = $first_curr;
        }
        return view('admin.order.receipt', compact('order', 'cart', 'first_curr', 'order_curr'));
    }

    public function manageReceipt($id, $action)
    {
        $data = Order::findOrFail($id);
        switch ($action) {
            case "accept":
                Order::where('id', $id)->update(['payment_status' => "Completed"]);
                $msg = __('The receipt has been accepted. Order status changed to Completed.');
                $this->sendReceiptUpdate($id, true);
                return response()->json(array('msg' => $msg));
                break;
            case "reject":
                Order::where('id', $id)->update(['receipt' => null]);
                if (file_exists(public_path().'/storage/images/receipts/'.$data->receipt)) {
                    unlink(public_path().'/storage/images/receipts/'.$data->receipt);
                }
                $msg = __('The receipt has been rejected.');
                $this->sendReceiptUpdate($id, false);
                return response()->json(['msg' => $msg, 'redirect' => true]);
                break;
        }
    }

    public function billetStatus($id)
    {
        $order = Order::findOrFail($id);
        $transaction_id = $order->txnid;
        $token = $this->storeSettings->paghiper_token;
        $api_key = $this->storeSettings->paghiper_api_key;

        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();

        $data = array(
            "token" => $token,
            "apiKey" => $api_key,
            "transaction_id" => $transaction_id
        );
        $data_post = json_encode($data);

        $mediaType = "application/json";
        $charSet = "UTF-8";

        $headers = array();
        $headers[] = "Accept: ".$mediaType;
        $headers[] = "Accept-Charset: ".$charSet;
        $headers[] = "Accept-Encoding: ".$mediaType;
        $headers[] = "Content-Type: ".$mediaType.";charset=".$charSet;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.paghiper.com/transaction/status/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($result = curl_exec($ch)) {
            $billet = json_decode($result, true);
            switch ($billet['status_request']['status']) {
                case "canceled":
                    $order->update(['payment_status' => "Pending", 'status' => "declined"]);
                    break;
                case "completed":
                    $order->update(['payment_status' => "Completed", 'status' => "processing"]);
                    break;
                case "paid":
                    $order->update(['payment_status' => "Completed", 'status' => "processing"]);
                    break;
            }

            $ddi = strlen($order->customer_phone) == "11" ? "55" : "595";
            $msg = "Olá ".$order->customer_name."! Estamos reenviando seu boleto no valor de ".
            $order->currency_sign.number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator).": ".$billet["status_request"]["bank_slip"]["url_slip"];
            return view('admin.order.billet', compact('order', 'data', 'order_curr', 'billet', 'msg', 'ddi'));
        } else {
            Log::debug('paghiper_curl_response', [$ch]);
            return redirect()->back();
        }
    }

    public function emailsub(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if ($gs->is_smtp == 1) {
            $data = 0;
            $datas = [
                    'to' => $request->to,
                    'subject' => $request->subject,
                    'body' => $request->message,
            ];

            $mailer = new GeniusMailer();
            $mail = $mailer->sendCustomMail($datas);
            if ($mail) {
                $data = 1;
            }
        } else {
            $data = 0;
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            $mail = mail($request->to, $request->subject, $request->message, $headers);
            if ($mail) {
                $data = 1;
            }
        }

        return response()->json($data);
    }

    public function printpage($id)
    {
        $order = Order::findOrFail($id);
        $cart = $order->cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
        $seos = Seotool::all();
        if (empty($order_curr)) {
            $order_curr = $first_curr;
        }
        return view('admin.order.print', compact('order', 'cart', 'first_curr', 'order_curr', 'seos'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = $order->cart;
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = $cart;
        $order->update();
        $msg = __('Successfully Changed The License Key.');
        return response()->json($msg);
    }

    public function sendOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($this->storeSettings->is_smtp == 1) {
            $data = [
                'to' => $order->customer_email,
                'type' => "new_order",
                'cname' => $order->customer_name,
                'oamount' => $order->pay_amount,
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
                'onumber' => $order->order_number,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoOrderMail($data, $order->id);
        } else {
            $to = $order->customer_email;
            $subject = __("Your Order Placed!!");
            $msg = $this->storeSettings->title . "\n" .__("Hello") . " " . $order->customer_name . "!\n" . __("You have placed a new order.") . "\n" .
                __("Your order number is") . " " . $this->order->order_number . "." . __("Please wait for your delivery.") . " \n"
                . __("Thank you");
            $headers = "From: " . $this->storeSettings->from_name . "<" . $this->storeSettings->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        Session::flash('success', __('Order :order sent by e-mail!', ['order' => $order->order_number]));
        return back();
    }

    public function sendReceiptUpdate($id, $status)
    {
        $order = Order::findOrFail($id);
        $subject = "Pedido ".$order->order_number." - Seu Comprovante foi ";
        $subject .= $status ? "aceito" : "recusado";
        $link = route('front.receipt-number', $order->order_number);
        $msgAccepted = 'Olá '.$order->customer_name.'! Seu comprovante de pagamento foi recebido e seu pedido será encaminhado para entrega.';
        $msgRejected = 'Olá '.$order->customer_name.'! Seu comprovante de pagamento foi recusado. Por favor, acesse seu pedido e envie um comprovante válido. <br><br><a href='.$link.'>Clique aqui para enviar o comprovante.</a>';
        $body = $status ? $msgAccepted : $msgRejected;
        if ($this->storeSettings->is_smtp == 1) {
            $data = [
                'to' => $order->customer_email,
                'subject' => $subject,
                'body' => $body,
            ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $to = $order->customer_email;
            $headers = "From: ".$this->storeSettings->from_name."<".$this->storeSettings->from_email.">";
            mail($to, $subject, $msg, $headers);
        }
    }

    public function updateMelhorenvioTrackings(Request $request)
    {
        Order::chunk(10, function ($orders) {
            foreach ($orders as $order) {
                try {
                    $order->melhorenvio_requests();
                } catch (\Exception $e) {
                    return response()->json(array('errors' => [ 0 => __('Trackings not updated') ]));
                }
            }
        });
        $msg = __('Successfully updated trackings');
        return response()->json($msg);
    }
}
