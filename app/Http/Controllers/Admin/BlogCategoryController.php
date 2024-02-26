<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = BlogCategory::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('name', function (BlogCategory $data){
                return $data->name . '<br><small>SLUG: '.$data->slug.'</small>';
            })
            ->addColumn('action', function (BlogCategory $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-cblog-edit', $data->id) . '" data-header="' . __('Edit Blog Category') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-cblog-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['name', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.cblog.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.cblog.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
                "{$this->lang->locale}.name" => 'required',
               'slug' => 'unique:blog_categories'
                ];
        $customs = [
                "{$this->lang->locale}.name.required" => __('Category Name in :lang is required',['lang' => $this->lang->language]),
               'slug.unique' => __('This slug has already been taken.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new BlogCategory;
        $input = $this->removeEmptyTranslations($request->all());
        $data->fill($input)->save();
        //--- Logic Section Ends
        //-----Creating automatic slug
        $blogCat = BlogCategory::find($data->id);
        $blogCat->slug = Str::slug($data->name,'-').'-'.strtolower($data->id);
        $blogCat->update();

        //--- Redirect Section  
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends  
    }

    //*** GET Request
    public function edit($id)
    {
        $data = BlogCategory::findOrFail($id);
        return view('admin.cblog.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
                "{$this->lang->locale}.name" => 'required',
               'slug' => 'unique:blog_categories,slug,'.$id
                ];
        $customs = [
                "{$this->lang->locale}.name.required" => __('Category Name in :lang is required',['lang' => $this->lang->language]),
               'slug.unique' => __('This slug has already been taken.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = BlogCategory::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        $data->update($input);
        //--- Logic Section Ends
        $data = BlogCategory::findOrFail($id);
        $data->slug = Str::slug($data->name,'-').'-'.strtolower($data->id);
        $data->update($input);

        //--- Redirect Section          
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends  

    }

    //*** GET Request
    public function destroy($id)
    {
        $data = BlogCategory::findOrFail($id);

        //--- Check If there any blogs available, If Available Then Delete it 
        if($data->blogs->count() > 0)
        {
            foreach ($data->blogs as $element) {
                $element->delete();
            }
        }
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}
