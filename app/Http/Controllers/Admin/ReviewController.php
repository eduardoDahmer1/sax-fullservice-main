<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Review::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->filterColumn('subtitle', function ($query, $keyword) {
                $query->whereTranslationLike('subtitle', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('photo', function (Review $data) {
                $photo = $data->photo ? url('storage/images/reviews/' . $data->photo) : url('assets/images/noimage.png');
                return '<img class="review-img" src="' . $photo . '" alt="Image">';
            })
            ->addColumn('action', function (Review $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-review-edit', $data->id) . '" data-header="' . __('Edit Review') . '" class="edit" data-toggle="modal" data-target="#modal1">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-review-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
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
        return view('admin.review.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.review.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.subtitle" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'photo'      => 'required|mimes:jpeg,jpg,png,svg,webp',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.subtitle.required" => __('Subtitle in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Description in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Review();
        $input = $this->removeEmptyTranslations($request->all());
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/reviews', $name);
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
        $data = Review::findOrFail($id);
        return view('admin.review.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.subtitle" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'photo'      => 'mimes:jpeg,jpg,png,svg,webp',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.subtitle.required" => __('Subtitle in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Description in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Review::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/reviews', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/storage/images/reviews/' . $data->photo)) {
                    unlink(public_path() . '/storage/images/reviews/' . $data->photo);
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
        $data = Review::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/reviews/'.$data->photo)) {
            unlink(public_path().'/storage/images/reviews/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
