<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Service::where('user_id', '=', 0)->orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->filterColumn('details', function ($query, $keyword) {
                $query->whereTranslationLike('details', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('photo', function (Service $data) {
                if (file_exists(public_path().'/storage/images/services/'.$data->photo)) {
                    return asset('storage/images/services/'.$data->photo);
                } else {
                    return asset('assets/images/noimage.png');
                }
            })
            ->editColumn('title', function (Service $data) {
                $title = mb_strlen(strip_tags($data->title), 'utf-8') > 250 ? mb_substr(strip_tags($data->title), 0, 250, 'utf-8') . '...' : strip_tags($data->title);
                return  $title;
            })
            ->editColumn('details', function (Service $data) {
                $details = mb_strlen(strip_tags($data->details), 'utf-8') > 250 ? mb_substr(strip_tags($data->details), 0, 250, 'utf-8') . '...' : strip_tags($data->details);
                return  $details;
            })
            ->addColumn('action', function (Service $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-service-edit', $data->id) . '" data-header="' . __('Edit Service') . '" class="edit" data-toggle="modal" data-target="#modal1">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-service-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['photo', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.service.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.service.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'photo'      => 'required|mimes:jpeg,jpg,png,svg',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Details in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Service();
        $input = $this->removeEmptyTranslations($request->all());
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/services', $name);
            $input['photo'] = $name;
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
        $data = Service::findOrFail($id);
        return view('admin.service.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'photo'      => 'mimes:jpeg,jpg,png,svg',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Details in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Service::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/services', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/storage/images/services/' . $data->photo)) {
                    unlink(public_path() . '/storage/images/services/' . $data->photo);
                }
            }
            $input['photo'] = $name;
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
        $data = Service::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/services/'.$data->photo)) {
            unlink(public_path().'/storage/images/services/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
