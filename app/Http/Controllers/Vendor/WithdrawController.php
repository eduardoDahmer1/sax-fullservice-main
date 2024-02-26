<?php

namespace App\Http\Controllers\Vendor;

use App\Models\User;
use App\Models\Withdraw;
use App\Models\Generalsetting;
use Auth;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Datatables;

class WithdrawController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();
            if(!$user->checkStatus())
            {
                return redirect()->route('vendor-verify');
            }
            return $next($request);
        });
    }

    public function datatables(){
        /*
        <td>{{$withdraw->created_at->diffForHumans()}}</td>
        <td>{{$sign->sign}}{{ round($withdraw->amount * $sign->value , 2) }}</td>
        <td>{{__(ucfirst($withdraw->status))}}</td>
        */
        $datas = Withdraw::where('user_id','=',Auth::guard('web')->user()->id)->where('type','=','vendor')->orderBy('id','desc');
        $sign = Currency::find(1);
        return Datatables::of($datas)
        ->editColumn('amount', function(Withdraw $data) use ($sign){
            return $sign->sign.round($data->amount * $sign->value , 2);
        })
        ->editColumn('status', function (Withdraw $data){
            $status = "";
            switch($data->status){
                case "pending":
                    $status = "warning";
                break;
                case "completed":
                    $status = "success";
                break;
                case "rejected": 
                    $status = "danger";
                break;
            }
            return '<span class="badge badge-'.$status.'">'.__(ucfirst($data->status)).'</span>';
        })
        ->rawColumns(['created_at', 'amount', 'status'])
        ->toJson();
    }

  	public function index()
    {
        return view('vendor.withdraw.index');
    }


    public function create()
    {
        $sign = Currency::find(1);
        return view('vendor.withdraw.create' ,compact('sign'));
    }


    public function store(Request $request)
    {

        $from = User::findOrFail(Auth::guard('web')->user()->id);
        $curr = Currency::find(1);
        $withdrawcharge = Generalsetting::findOrFail(1);
        $charge = $withdrawcharge->withdraw_fee;

        if($request->amount > 0){

            $amount = $request->amount;
            $amount = round(($amount / $curr->value),2);
            if ($from->current_balance >= $amount){
                $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
                $finalamount = $amount - $fee;
                $finalamount = number_format((float)$finalamount,2,'.','');

                $from->current_balance = $from->current_balance - $amount;
                $from->update();

                $newwithdraw = new Withdraw();
                $newwithdraw['user_id'] = Auth::user()->id;
                $newwithdraw['reference'] = $request->reference;
                $newwithdraw['amount'] = $finalamount;
                $newwithdraw['fee'] = $fee;
                $newwithdraw['type'] = 'vendor';
                $newwithdraw->save();

                return response()->json(__('Withdraw Request Sent Successfully.')); 

            }else{
                 return response()->json(array('errors' => [ 0 => __('Insufficient Balance.') ])); 
            }
        }
            return response()->json(array('errors' => [ 0 => __('Please enter a valid amount.') ])); 

    }
}
