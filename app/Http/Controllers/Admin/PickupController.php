<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pickup;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PickupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Pickup::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('location', function ($query, $keyword) {
                $query->whereTranslationLike('location', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('action', function (Pickup $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-pick-edit', $data->id) . '" data-header="' . __('Edit Pickup') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-pick-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.pickup.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.pickup.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.location" => "required"
        ];
        $customs = [
            "{$this->lang->locale}.location.required" => __("Location in :lang is required", ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Pickup;
        $input = $this->removeEmptyTranslations($request->all());
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
        $data = Pickup::findOrFail($id);
        return view('admin.pickup.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.location" => "required"
        ];
        $customs = [
            "{$this->lang->locale}.location.required" => __("Location in :lang is required", ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Pickup::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section          
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends  

    }

    //*** GET Request
    public function destroy($id)
    {
        $data = Pickup::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}
