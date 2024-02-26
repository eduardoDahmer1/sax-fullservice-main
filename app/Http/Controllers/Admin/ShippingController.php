<?php

namespace App\Http\Controllers\Admin;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use NumberFormatter;

class ShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Shipping::orderBy('id');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('has_region', function(Shipping $data){
                return ($data->is_region) ? __("Yes") : __("No");
            })
            ->editColumn('price', function (Shipping $data) {
                $sign = Currency::where('id', '=', 1)->first();
                
                if($data->shipping_type !== "Percentage Price"){
                    $price = $sign->sign . $data->price;
                }
                
                if ($data->shipping_type == "Percentage Price") {
                    $formatted_percentage_price = floatval($data->price) / 100;
                    $formatter = new NumberFormatter($this->lang->locale, NumberFormatter::PERCENT);
                    $price = $formatter->format($formatted_percentage_price);
                }

                return  $price;
            })
            ->editColumn('country_id', function (Shipping $data) {
                return ($data->country_id ? $data->country->country_name : '-');
            })
            ->editColumn('state_id', function (Shipping $data) {
                return ($data->state_id ? $data->state->name : '-');
            })
            ->editColumn('city_id', function (Shipping $data) {
                return ($data->city_id ? $data->city->name : '-');
            })
            ->addColumn('status', function (Shipping $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label  class="switch"><input type="checkbox"  class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-shipping-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('action', function (Shipping $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-shipping-edit', $data->id) . '" data-header="' . __('Edit Shipping') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-shipping-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.shipping.index');
    }

    //*** GET Request
    public function create()
    {
        $sign = Currency::where('id','=',1)->first();

        $countries = Country::all();
        return view('admin.shipping.create',compact('sign','countries'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => "required",
            'price'               => 'required',
            'shipping_type'       => 'required',
        ];

        $customs = [
            "{$this->lang->locale}.title.required" => __("Title in :lang is required", ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Shipping();
        $input = $this->withRequiredFields($request->all(), ['title']);
        if ($request->status == "") {
            $input['status'] = 0;
        }
        if ($request->local_shipping == "") {
            $input['local_shipping'] = 0;
        }
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
        $sign = Currency::where('id','=',1)->first();
        $data = Shipping::findOrFail($id);

        $states = State::where('country_id',$data->country_id)->get();
        $cities = City::where('state_id',$data->state_id)->get();
        $countries = Country::all();

        return view('admin.shipping.edit',compact('data','sign','countries','states','cities'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => "required"
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __("Title in :lang is required", ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Shipping::findOrFail($id);

        $input = $this->withRequiredFields($request->all(), ['title']);

        if ($request->local_shipping == "") {
            $input['local_shipping'] = 0;
        }

        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends            
    }

       //*** GET Request Status
       public function status($id1,$id2)
       {
           $data = Shipping::findOrFail($id1);
           $data->status = $id2;
           $data->update();
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
