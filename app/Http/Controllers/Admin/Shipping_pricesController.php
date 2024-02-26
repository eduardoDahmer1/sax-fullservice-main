<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;

use App\Models\State;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\Shipping_prices;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Shipping_pricesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Shipping_prices::orderBy('id');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('shipping_id', function (Shipping_prices $data) {
                $shipping = Shipping::where('id', '=', $data['shipping_id'])->first();
                if (!empty($shipping)) {
                    return  $shipping->title;
                }
            })
            ->editColumn('country_id', function (Shipping_prices $data) {
                $country = Country::where('id', '=', $data['country_id'])->first();
                if (!empty($country)) {
                    return  $country->country_name;
                }
            })
            ->editColumn('state_id', function (Shipping_prices $data) {
                $state = State::where('id', '=', $data['state_id'])->first();
                if (!empty($state)) {
                    return  $state->name;
                }
            })
            ->editColumn('city_id', function (Shipping_prices $data) {
                $city = City::where('id', '=', $data['city_id'])->first();
                if (!empty($city)) {
                    return  $city->name;
                }
            })
            ->addColumn('action', function (Shipping_prices $data) {
                return '<div class="action-list"><a data-href="' . route('admin-shipping_prices-edit', $data->id) . '" data-header="' . __('Edit Shipping Price') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>' . __('Edit') . '</a><a href="javascript:;" data-href="' . route('admin-shipping_prices-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.shipping_prices.index');
    }

    //*** GET Request
    public function create()
    {
        $sign = Currency::where('id','=',1)->first();
        $countries = Country::all();
        
        $shippings = Shipping::all();
        return view('admin.shipping_prices.create',compact('sign', 'countries', 'shippings'));
    }

    //*** POST Request
    public function store(Request $request)
    {

        //--- Validation Section
        $rules = [
            'shipping_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Shipping_prices();
        $input = $this->removeEmptyTranslations($request->all());

        if ($request->status == "") {
            $input['status'] = 0;
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
        $data = Shipping_prices::findOrFail($id);
        $shippings = Shipping::all();
        
        $states = State::where('country_id',$data->country_id)->get();
        $cities = City::where('state_id',$data->state_id)->get();
        $countries = Country::all();

        return view('admin.shipping_prices.edit',compact('sign', 'data', 'countries', 'states', 'cities', 'shippings', 'countries'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        //--- Validation Section
        $rules = [
            'shipping_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Shipping_prices::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);

        if ($request->city_id == "") {
            $input['city_id'] = null;
        }

        if ($request->status == "") {
            $input['status'] = 0;
        }

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
        $data = Shipping_prices::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }

    public function getStates(Request $request)
    {
        if ($request->country_id) {

            $states = State::all()->where('country_id', $request->country_id);
            $options = "<option value=''>" . __('Select State') . "</option>";

            foreach($states as $state){
                $options .= "<option value='".$state->id."'>".$state->name."</option>";
            }
            return $options;

        }

        if ($request->state_id) {

            $cities = City::all()->where('state_id', $request->state_id);
            $options = "<option value=''>" . __('Select City') . "</option>";

            foreach($cities as $city){
                $options .= "<option value='".$city->id."'>".$city->name."</option>";
            }
            return $options;
        }

    }

}
