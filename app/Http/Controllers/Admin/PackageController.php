<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\Currency;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Package::orderBy('id');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('price', function (Package $data) {
                $sign = Currency::where('id', '=', 1)->first();
                $price = number_format($data->price * $sign->value, $sign->decimal_digits, $sign->decimal_separator, $sign->thousands_separator);
                $price = $sign->sign . $price;
                return  $price;
            })
            ->addColumn('action', function (Package $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-package-edit', $data->id) . '" data-header="' . __('Edit Package') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-package-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
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
        return view('admin.package.index');
    }

    //*** GET Request
    public function create()
    {
        $sign = Currency::where('id','=',1)->first();
        return view('admin.package.create',compact('sign'));
    }

    //*** POST Request
    public function store(Request $request)
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
        $data = new Package();
        $input = $this->withRequiredFields($request->all(), ['title']);
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
        $data = Package::findOrFail($id);
        return view('admin.package.edit',compact('data','sign'));
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
        $data = Package::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['title']);
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
        $data = Package::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }
}
