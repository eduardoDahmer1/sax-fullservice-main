<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Partner::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('photo', function (Partner $data) {
                $photo = $data->photo ? url('storage/images/partner/' . $data->photo) : url('assets/images/noimage.png');
                return '<img class="partner-logo" src="' . $photo . '" alt="Image">';
            })
            ->addColumn('action', function (Partner $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-partner-edit', $data->id) . '" data-header="' . __('Edit Partner') . '" class="edit" data-toggle="modal" data-target="#modal1">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-partner-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
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
        return view('admin.partner.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.partner.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'link'       => 'required',
               'photo'      => 'required|mimes:jpeg,jpg,png,svg,webp',
                ];

        $customs = [
                'link.required' => __('The link field is required')
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Partner();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/partner', $name);
            $input['photo'] = $name;
        }
        if (!empty($request->meta_tag)) {
            $input['meta_tag'] = implode(',', $request->meta_tag);
        }
        if (!empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
        }
        if ($request->secheck == "") {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
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
        $data = Partner::findOrFail($id);
        return view('admin.partner.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
               'link'       => 'required',
               'photo'      => 'mimes:jpeg,jpg,png,svg,webp',
                ];

        $customs = [
               'link.required' => __('The link field is required')
                ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Partner::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/partner', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/storage/images/partner/'.$data->photo)) {
                    unlink(public_path().'/storage/images/partner/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }
        if (!empty($request->meta_tag)) {
            $input['meta_tag'] = implode(',', $request->meta_tag);
        } else {
            $input['meta_tag'] = null;
        }
        if (!empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
        } else {
            $input['tags'] = null;
        }
        if ($request->secheck == "") {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
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
        $data = Partner::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/partner/'.$data->photo)) {
            unlink(public_path().'/storage/images/partner/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
