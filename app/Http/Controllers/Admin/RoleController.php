<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Role::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('section', function (Role $data) {
                $details =  str_replace('_', ' ', $data->section);
                $details =  ucwords($details);
                $arr_details = explode(" , ", $details);
                foreach ($arr_details as $key => $t_detail) {
                    $arr_details[$key] = __($t_detail);
                }
                $details = implode(" , ", $arr_details);
                return  '<div>' . $details . '</div>';
            })
            ->addColumn('action', function (Role $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-role-edit', $data->id) . '">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-role-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['section', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.role.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.role.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'photo'      => '',
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Name in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Role();
        $input = $this->removeEmptyTranslations($request->all());
        if (!empty($request->section)) {
            $input['section'] = implode(" , ", $request->section);
        } else {
            $input['section'] = '';
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
        $data = Role::findOrFail($id);
        return view('admin.role.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'photo'      => '',
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Name in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Role::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        if (!empty($request->section)) {
            $input['section'] = implode(" , ", $request->section);
        } else {
            $input['section'] = '';
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
        $data = Role::findOrFail($id);
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
