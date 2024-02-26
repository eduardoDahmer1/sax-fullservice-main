<?php

namespace App\Http\Controllers\User;

use Image;
use Validator;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seotool;
use App\Models\Currency;
use App\Models\BankAccount;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!app()->runningInConsole()) {
            if (!$this->storeSettings->is_cart) {
                return app()->abort(404);
            }
        }

        $this->middleware('auth');
    }

    public function orders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id', '=', $user->id)->orderBy('id', 'desc')->get();
        $currencies = Currency::orderBy('id')->get();
        return view('user.order.index', compact('user', 'orders', 'currencies'));
    }

    public function ordertrack()
    {
        $user = Auth::guard('web')->user();
        return view('user.order-track', compact('user'));
    }

    public function trackload($id)
    {
        $order = Order::where('order_number', '=', $id)->first();
        $datas = array('Pending','Processing','On Delivery','Completed');
        return view('load.track-load', compact('order', 'datas'));
    }


    public function order($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::findOrfail($id);
        $cart =$order->cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
        if (empty($order_curr)) {
            $order_curr = $first_curr;
        }

        $bank_accounts =  BankAccount::where('status', '=', 1)->get();

        return view('user.order.details', compact('user', 'order', 'cart', 'first_curr', 'order_curr', 'bank_accounts'));
    }

    public function orderdownload($slug, $id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::where('order_number', '=', $slug)->first();
        $prod = Product::findOrFail($id);
        if (!isset($order) || $prod->type == 'Physical' || $order->user_id != $user->id) {
            return redirect()->back();
        }
        return response()->download(public_path('assets/files/'.$prod->file));
    }

    public function orderprint($id)
    {
        $user = Auth::guard('web')->user();
        $order = Order::findOrfail($id);
        $cart = $order->cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $order->currency_sign)->first();
        $seos = Seotool::all();
        if (empty($order_curr)) {
            $order_curr = $first_curr;
        }

        $bank_accounts =  BankAccount::where('status', '=', 1)->get();

        return view('user.order.print', compact('user', 'order', 'cart', 'first_curr', 'order_curr', 'bank_accounts', 'seos'));
    }

    public function trans()
    {
        $id = $_GET['id'];
        $trans = $_GET['tin'];
        $order = Order::findOrFail($id);
        $order->txnid = $trans;
        $order->update();
        $data = $order->txnid;
        return response()->json($data);
    }

    public function uploadReceiptGet($id)
    {
        $order = Order::findOrFail($id);
        return view('user.order.receipt', compact('order'));
    }

    public function uploadReceipt(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'receipt' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $data = Order::findOrFail($request->id);
        if (!is_dir(public_path().'/storage/images/receipts/')) {
            mkdir(public_path().'/storage/images/receipts/');
        }
        //--- Validation Section Ends
        $image = $request->receipt;
        $image = base64_decode($image);
        $image_name = time().Str::random(8).'.png';
        $path = 'storage/images/receipts/'.$image_name;
        $mime = mime_content_type($request->file('receipt')->getRealPath());
        if ($mime == "image/jpeg" || $mime == "image/png" || $mime == "image/gif" || $mime == "image/webp") {
            $img = Image::make($request->file('receipt')->getRealPath());
            $img->save(public_path().'/storage/images/receipts/'.$image_name);
            if ($data->receipt != null) {
                if (file_exists(public_path().'/storage/images/receipts/'.$data->receipt)) {
                    unlink(public_path().'/storage/images/receipts/'.$data->receipt);
                }
            }
            Order::where('id', $request->id)->update(['receipt' => $image_name]);
            $notification = new Notification;
            $notification->receipt = $image_name;
            $notification->order_id = $data->id;
            $notification->save();
            return response()->json(['success'=> true, 'msg' => "Comprovante enviado com sucesso!"]);
        } else {
            return response()->json(['success'=> false, 'msg' => "Formato de arquivo inválido!"]);
        }
    }
}
