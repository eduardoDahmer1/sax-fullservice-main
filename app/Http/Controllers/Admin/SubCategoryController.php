<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SubCategoryController extends Controller
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
        $datas = Subcategory::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('category', function (Subcategory $data) {
                return $data->category->name;
            })
            ->addColumn('status', function (Subcategory $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-subcat-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('products', function (Subcategory $data) {
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
            ->addColumn('action', function (Subcategory $data) {
                return '
                <div class="godropdown">
                <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-attr-manage', $data->id) . '?type=subcategory' . '" class="edit"> <i class="fas fa-edit"></i> ' . __('Manage Attributes') . '</a>
                        <a data-href="' . route('admin-subcat-edit', $data->id) . '" data-header="' . __('Edit Sub Category') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit Sub Category') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-subcat-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
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
        return view('admin.subcategory.index');
    }

    //*** GET Request
    public function create()
    {
        $cats = Category::all();
        return view('admin.subcategory.create', compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'category_id'      => 'required',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'slug' => 'unique:subcategories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Subcategory Name in :lang is required', ['lang' => $this->lang->language]),
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
        $category = Category::find($request->category_id);
        if (empty($category)) {
            if ($request->api) {
                return response()->json(array('errors' => [__('Category not found')]), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => [__('Category not found')]));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Subcategory();
        $input = $this->removeEmptyTranslations($request->all());

        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/subcategories/banners', $name);
            $input['banner'] = $name;
        }

        $data->fill($input)->save();
        //--- Logic Section Ends

        //-----Creating automatic slug
        $subcat = Subcategory::find($data->id);
        $subcat->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $subcat->update();

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
        $data = Subcategory::findOrFail($id);
        return view('admin.subcategory.edit', compact('data', 'cats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'category_id'      => 'required',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'slug' => 'unique:subcategories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Subcategory Name in :lang is required', ['lang' => $this->lang->language]),
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
        $category = Category::find($request->category_id);
        if (empty($category)) {
            if ($request->api) {
                return response()->json(array('errors' => [__('Category not found')]), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => [__('Category not found')]));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Subcategory::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);

        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/subcategories/banners', $name);
            if ($data->banner != null) {
                if (file_exists(public_path().'/storage/images/subcategories/banners/'.$data->banner) && !empty($data->banner)) {
                    unlink(public_path().'/storage/images/subcategories/banners/'.$data->banner);
                }
            }
            $input['banner'] = $name;
        }

        $data->update($input);
        //--- Logic Section Ends
        //----Slug automatic
        $data = Subcategory::findOrFail($id);
        $data->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $data->update($input);

        //--- Redirect Section
        if ($request->api) {
            return response()->json(array('status' => 'ok'));
        }
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = Subcategory::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** GET Request
    public function load($id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory', compact('cat'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Subcategory::findOrFail($id);


        //If attributes exist
        if ($data->attributes->count() > 0) {
            Attribute::where('attributable_id', $id)->where('attributable_type', '=', 'App\Models\Subcategory')->delete();
        }

        //If childcategory exist
        if ($data->childs->count() > 0) {
            $childId = $data->childs()->get()->pluck('id');
            Attribute::where('attributable_id', $childId)->where('attributable_type', '=', 'App\Models\Childcategory')->delete();
        }

        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
