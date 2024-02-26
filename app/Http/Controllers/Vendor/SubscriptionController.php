<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Package;
use Datatables;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use DB;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public $global_language;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();
            if($user->checkWarning())
            {
                return redirect()->route('vendor-warning',$user->verifies()->where('admin_warning','=','1')->orderBy('id','desc')->first()->id);
            }
            if(!$user->checkStatus())
            {
                return redirect()->route('vendor-verify');
            }
            return $next($request);
        });
    }

    public function index(){
        if(!config("features.marketplace")) {
            return redirect()->route("user-dashboard")->withErrors("Marketplace not enabled");
        }
        $user = Auth::user();
        $subs = Subscription::all();
        $package = $user->subscribes()->where('status',1)->orderBy('id','desc')->first();
        return view('vendor.subscription.index',compact('user','subs','package'));
    }
}