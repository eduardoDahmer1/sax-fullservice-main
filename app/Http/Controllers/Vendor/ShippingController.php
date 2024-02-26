<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Shipping;
use Datatables;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use DB;

class ShippingController extends Controller
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

            if (Session::has('language')) 
            {
                $data = Language::find(Session::get('language'));
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
            }
            else
            {
                $data = Language::where('is_default','=',1)->first();
                $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
                $this->vendor_language = json_decode($data_results);
                
            } 

    }

    //*** JSON Request
    public function datatables()
    {
         $datas = Shipping::where('user_id',Auth::user()->id)->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('price', function(Shipping $data) {
                                $sign = $curr = Currency::find($this->storeSettings->currency_id);
                                $price = $sign->sign.$data->price;
                                return  $price;
                            })
                            ->addColumn('action', function(Shipping $data) {
                                return '<div class="action-list"><a data-href="' . route('vendor-shipping-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript:;" data-href="' . route('vendor-shipping-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('vendor.shipping.index');
    }

    //*** GET Request
    public function create()
    {
        $sign = $curr = Currency::find($this->storeSettings->currency_id);
        return view('vendor.shipping.create',compact('sign'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = ['title' => 'unique:shippings'];
        $customs = ['title.unique' => 'This title has already been taken.'];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Shipping();
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($id)
    {
        $sign = $curr = Currency::find($this->storeSettings->currency_id);
        $data = Shipping::findOrFail($id);
        return view('vendor.shipping.edit',compact('data','sign'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = ['title' => 'unique:shippings,title,'.$id];
        $customs = ['title.unique' => 'This title has already been taken.'];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Shipping::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends            
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Shipping::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }
}