<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Currency;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Subscription::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('price', function (Subscription $data) {
                $sign = Currency::where('id', '=', 1)->first();
                $price = number_format($data->price * $sign->value, $sign->decimal_digits, $sign->decimal_separator, $sign->thousands_separator);
                $price = $sign->sign . $price;
                return $price;
            })
            ->editColumn('allowed_products', function (Subscription $data) {
                $allowed_products = $data->allowed_products == 0 ? __("Unlimited") : $data->allowed_products;
                return $allowed_products;
            })
            ->addColumn('action', function (Subscription $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-subscription-edit', $data->id) . '" data-header="' . __('Edit Subscription') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-subscription-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        if(!config("features.marketplace")) {
            return redirect()->route("admin.dashboard")->withErrors("Marketplace is not enabled");
        }

        return view('admin.subscription.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.subscription.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Subscription Title in :lang is required', ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Subscription();
        $input = $this->withRequiredFields($request->all(), ['title']);

        if ($input['limit'] == 0) {
            $input['allowed_products'] = 0;
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
        $data = Subscription::findOrFail($id);
        return view('admin.subscription.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

         //--- Validation Section
         $rules = [
            "{$this->lang->locale}.title" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Subscription Title in :lang is required', ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Subscription::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['title']);
        if($input['limit'] == 0)
         {
            $input['allowed_products'] = 0;
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
        $data = Subscription::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }
}
