<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartAbandonment;
use Yajra\DataTables\DataTables;
use App\Classes\GeniusMailer;
use App\Models\Currency;

class CartAbandonmentController extends Controller
{
    public function __construct()
    {
        {
            $this->middleware('auth:admin');
            parent::__construct();
        }
    }

    public function datatables()
    {
        $datas = CartAbandonment::all();
        return Datatables::of($datas)
        ->addColumn('action', function (CartAbandonment $data) {
            return '
            <div class="godropdown">
                <button class="go-dropdown-toggle">' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>

                <div class="action-list">
                    <a class="sendAbandonmentEmail" data-href="' . route('admin-cartabandonment-sendmail', $data->id) . '"><i class="fas fa-envelope"></i> ' . __('Email') . '</a>
                    <a href="' . route('admin-cartabandonment-details', $data->id) . '"><i class="fas fa-search"></i> ' . __('Details') . '</a>
                    <a data-href="' . route('admin-cartabandonment-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash"></i> ' . __('Delete') . '</a>
                </div>
            </div>';
        })
        ->editColumn('name', function (CartAbandonment $data) {
            return $data->user->name;
        })
        ->editColumn('email', function (CartAbandonment $data) {
            return $data->user->email;
        })
        ->editColumn('email_sent', function (CartAbandonment $data) {
            return ($data->email_sent) ? __("Yes") : __("No");
        })
        ->addColumn('qty', function (CartAbandonment $data) {
            return count($data->temp_cart->items ?? []);
        })
        ->rawColumns(['action'])
        ->toJson(); //---
    }

    public function index()
    {
        if (!$this->storeSettings->is_cart_abandonment) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.cartabandonment.index');
    }


    public function details($id)
    {
        $ca = CartAbandonment::where('id', $id)->first();
        $cart = $ca->temp_cart;
        $first_curr = Currency::where('id', '=', 1)->first();
        $order_curr = Currency::where('sign', '=', $first_curr->sign)->first();
        return view('admin.cartabandonment.cart', compact('cart', 'first_curr', 'order_curr', 'id'));
    }

    public function sendMail($id)
    {
        $ca = CartAbandonment::where('id', $id)->first();
        if ($ca->email_sent) {
            return response()->json(array('errors' => [__("E-mail already sent!")]));
        }
        $user = $ca->user;
        if ($this->storeSettings->is_smtp == 1) {
            $data = [
                'to' => $user->email,
                'type' => "cart_abandonment",
                'cname' => $user->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'onumber' => "",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAbandonMail($data, $id);
            $ca->email_sent = true;
            $ca->update();
            $msg = __("E-mail sent successfully!");
            return response()->json($msg);
        }
    }

    public function destroy($id)
    {
        $ca = CartAbandonment::findOrFail($id);
        $ca->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
