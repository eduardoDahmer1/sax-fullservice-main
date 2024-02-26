<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ChildCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $this->useStoreLocale();
        $datas = Childcategory::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('category', function (Childcategory $data) {
                return $data->subcategory->category->name;
            })
            ->addColumn('subcategory', function (Childcategory $data) {
                return $data->subcategory->name;
            })
            ->addColumn('status', function (Childcategory $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-childcat-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('products', function (Childcategory $data) {
                $buttons = __('None');
                if (config("features.marketplace")) {
                    $products_count = $data->products()->where('user_id', 0)->count();
                } else {
                    $products_count = $data->products()->count();
                }
                if ($data->products()->where('status', 1)->count() > 0) {
                    $buttons = '<div class="ml-4">';
                    $buttons .= $products_count;
                    $buttons .= '</div>';
                }

                return $buttons;
            })
            ->addColumn('action', function (Childcategory $data) {
                return '
                <div class="godropdown">
                <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-attr-manage', $data->id) . '?type=childcategory' . '" class="edit"> <i class="fas fa-edit"></i> ' . __('Manage Attributes') . '</a>
                        <a data-href="' . route('admin-childcat-edit', $data->id) . '" data-header="' . __('Edit Child Category') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit Child Category') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-childcat-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                    </div>
                </div>';
            })
            ->rawColumns(['status', 'attributes', 'products', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
        $this->useAdminLocale();
    }



    //*** GET Request
    public function index()
    {
        // just a comment
        return view('admin.childcategory.index');
    }

    //*** GET Request
    public function create()
    {
        $cats = Category::all();
        return view('admin.childcategory.create', compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'subcategory_id'      => 'required',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'slug' => 'unique:childcategories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Category Name in :lang is required', ['lang' => $this->lang->language]),
            'slug.unique' => __('This slug has already been taken.'),
            'banner' => __('Banner Type is Invalid.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            if ($request->api) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $subcategory = SubCategory::find($request->subcategory_id);
        if (empty($subcategory)) {
            if ($request->api) {
                return response()->json(array('errors' => [__('Sub category not found')]), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => [__('Sub category not found')]));
        }
        if (empty($request->category_id)) {
            $request->category_id = $subcategory->category_id;
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Childcategory();
        $input = $this->removeEmptyTranslations($request->all());

        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/childcategories/banners', $name);
            $input['banner'] = $name;
        }

        $data->fill($input)->save();
        //--- Logic Section Ends

        //-----Creating automatic slug
        $childcat = Childcategory::find($data->id);
        $childcat->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $childcat->update();

        //--- Redirect Section
        if ($request->api) {
            return response()->json(array('status' => 'ok'), Response::HTTP_CREATED);
        }

        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $cats = Category::all();
        $subcats = Subcategory::all();
        $data = Childcategory::findOrFail($id);
        return view('admin.childcategory.edit', compact('data', 'cats', 'subcats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'subcategory_id'      => 'required',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'slug' => 'unique:childcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Category Name in :lang is required', ['lang' => $this->lang->language]),
            'slug.unique' => __('This slug has already been taken.'),
            'banner' => __('Banner Type is Invalid.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            if ($request->api) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $subcategory = SubCategory::find($request->subcategory_id);
        if (empty($subcategory)) {
            if ($request->api) {
                return response()->json(array('errors' => [__('Sub category not found')]), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => [__('Sub category not found')]));
        }
        if (empty($request->category_id)) {
            $request->category_id = $subcategory->category_id;
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Childcategory::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);

        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/childcategories/banners', $name);
            if ($data->banner != null) {
                if (file_exists(public_path().'/storage/images/childcategories/banners/'.$data->banner) && !empty($data->banner)) {
                    unlink(public_path().'/storage/images/childcategories/banners/'.$data->banner);
                }
            }
            $input['banner'] = $name;
        }

        $data->update($input);
        //--- Logic Section Ends

        //----Slug automatic
        $data = Childcategory::findOrFail($id);
        $data->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $data->update($input);

        //--- Redirect Section
        if ($request->api) {
            return response()->json(array('status' => 'ok'), Response::HTTP_CREATED);
        }
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = Childcategory::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** GET Request
    public function load($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory', compact('subcat'));
    }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Childcategory::findOrFail($id);

        //If attribute exist
        if ($data->attributes->count()>0) {
            Attribute::where('attributable_id', $id)->where('attributable_type', '=', 'App\Models\Childcategory')->delete();
        }

        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
