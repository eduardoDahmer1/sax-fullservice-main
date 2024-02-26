<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Slider::orderBy('id', 'desc');

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title_text', function ($query, $keyword) {
                $query->whereTranslationLike('title_text', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('photo', function (Slider $data) {
                $photo = $data->photo ? url('storage/images/sliders/' . $data->photo) : url('assets/images/noimage.png');
                return '<img src="' . $photo . '" alt="Image">';
            })
            ->editColumn('title_text', function (Slider $data) {
                $title = mb_strlen(strip_tags($data->title_text), 'utf-8') > 250 ? mb_substr(strip_tags($data->title_text), 0, 250, 'utf-8') . '...' : strip_tags($data->title_text);
                return  $title;
            })
            ->editColumn('updated_at', function (Slider $data) {
                setlocale(LC_ALL, \App\Helpers\Helper::strLocaleVariations($this->lang->locale));
                $str='<div class="action-list">' . $data->updated_at->formatLocalized('%d/%m/%Y, %T') . '</div>';
                return ($str);
            })
            ->addColumn('presentation_position', function (Slider $data) {
                return '<div><input min="0" type="number" class="presentation-pos" id="slider_'.$data->id.'" data-slide="'.$data->id.'" value="'.$data->presentation_position.'"></div>';
            })
            ->addColumn('status', function (Slider $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label  class="switch"><input type="checkbox"  class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-sl-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('action', function (Slider $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-sl-edit', $data->id) . '">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-sl-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['photo','status','updated_at','presentation_position', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.slider.index');
    }

    //*** GET Request
    public function status($id1, $id2)
    {
        $data = Slider::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    public function changeSliderPos($id, $pos)
    {
        $data = Slider::findOrFail($id);
        $data->presentation_position = $pos;
        $data->update();
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
    }

    //*** GET Request
    public function create()
    {
        $storesList = Generalsetting::all();
        return view('admin.slider.create', compact('storesList'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg,webp,gif',
               "{$this->lang->locale}.name" => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Slider();
        $input = $this->withRequiredFields($request->all(), ['name']);
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $slider_name = str_replace(' ', '-', $name);
            $file->move('storage/images/sliders', $slider_name);
            $input['photo'] = $slider_name;
        }
        $data->fill($input)->save();
        //--- Logic Section Ends

        $slider = Slider::find($data->id);

        if ($request->has('stores')) {
            $slider->stores()->sync($input['stores']);
        }

        //--- Redirect Section
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Slider::findOrFail($id);
        $storesList = Generalsetting::all();
        $currentStores = $data->stores()->pluck('id')->toArray();
        return view('admin.slider.edit', compact('data', 'storesList', 'currentStores'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'mimes:jpeg,jpg,png,svg,webp,gif,webp',
                ];

        $customs = ["{$this->lang->locale}.name.required" => __('Slider Name in :lang is required', ['lang' => $this->lang->language])];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Slider::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['name']);
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/sliders', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/storage/images/sliders/'.$data->photo)) {
                    unlink(public_path().'/storage/images/sliders/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        //--- Logic Section Ends

        //associates with stores
        $data->stores()->detach();
        if ($request->has('stores')) {
            $data->stores()->sync($input['stores']);
        }

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Slider::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/sliders/'.$data->photo)) {
            unlink(public_path().'/storage/images/sliders/'.$data->photo);
        }

        //remove from any store
        $data->stores()->detach();

        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
