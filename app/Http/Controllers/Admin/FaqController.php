<?php

namespace App\Http\Controllers\Admin;
use Yajra\DataTables\DataTables;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Faq::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->filterColumn('details', function ($query, $keyword) {
                $query->whereTranslationLike('details', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('details', function (Faq $data) {
                $details = mb_strlen(strip_tags($data->details), 'utf-8') > 250 ? mb_substr(strip_tags($data->details), 0, 250, 'utf-8') . '...' : strip_tags($data->details);
                return  $details;
            })
            ->addColumn('action', function (Faq $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-faq-edit', $data->id) . '" data-header="' . __('Edit Faq') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-faq-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
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
        return view('admin.faq.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.faq.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
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
        $data = new Faq();
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
        $data = Faq::findOrFail($id);
        return view('admin.faq.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Details in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        //--- Logic Section
        $data = Faq::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
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
        $data = Faq::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}
