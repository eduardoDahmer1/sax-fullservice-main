<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Language;
use App\Models\Attribute;
use App\Models\Subcategory;
use App\Models\CategoryGallery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        parent::__construct();
    }

    //*** JSON Request
    public function datatables($filter = null)
    {
        $this->useStoreLocale();
        $datas = Category::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        if ($filter == 'active') {
            $datas = $datas->active()->orderBy('id', 'desc');
        } elseif ($filter == "inactive") {
            $datas = $datas->inactive()->orderBy('id', 'desc');
        }elseif ($filter == 'with_products') {
            $datas = $datas->withProducts()->orderBy('id', 'desc');
        }elseif ($filter == 'no_products') {
            $datas = $datas->withoutProducts()->orderBy('id', 'desc');
        }
        return Datatables::of($datas)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('status', function (Category $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-cat-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('presentation_position', function (Category $data) {
                return '<div><input min="0" type="number" class="presentation-pos" id="cat_'.$data->id.'" data-cat="'.$data->id.'" value="'.$data->presentation_position.'"></div>';
            })
            ->addColumn('products', function (Category $data) {
                $buttons = __('None');
                if (config("features.marketplace")) {
                    $products_count = $data->products()->where('user_id', 0)->count();
                } else {
                    $products_count = $data->products()->count();
                }
                if ($products_count > 0) {
                    $buttons = '<div class="ml-4">';
                    $buttons .= $products_count;
                    $buttons .= '</div>';
                }

                return $buttons;
            })
            ->addColumn('attr_count', function (Category $data) {
                return $data->attributes()->count();
            })
            ->addColumn('action', function (Category $data) {
                $customProducts = env("ENABLE_CUSTOM_PRODUCT", false);
                if ($customProducts && $data->is_customizable == 1) {
                    return '<div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') .  '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-attr-manage', $data->id) . '?type=category' . '" class="edit"> <i class="fas fa-edit"></i> ' . __('Manage Attributes') . '</a>
                        <a data-href="' . route('admin-cat-edit', $data->id) . '" data-header="' . __('Edit Category') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                        <a data-href="'.route('admin-categorygallery-open', $data->id).'" data-header="'.__("Image Gallery").'" class="set-gallery" data-toggle="modal" data-target="#setgallery"><i class="fas fa-eye"></i> ' . __('View Gallery') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-cat-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                    </div>';
                } else {
                    return '<div class="godropdown">
                <button class="go-dropdown-toggle"> ' . __('Actions') .  '<i class="fas fa-chevron-down"></i></button><div class="action-list">
                    <a href="' . route('admin-attr-manage', $data->id) . '?type=category' . '" class="edit"> <i class="fas fa-edit"></i> ' . __('Manage Attributes') . '</a>
                    <a data-href="' . route('admin-cat-edit', $data->id) . '" data-header="' . __('Edit Category') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                    <a href="javascript:;" data-href="' . route('admin-cat-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                </div>';
                }
            })
            ->rawColumns(['status', 'products','presentation_position','action', 'attr_count'])
            ->toJson(); //--- Returning Json Data To Client Side
        $this->useAdminLocale();
    }

    //*** GET Request
    public function index()
    {
        $filters = [
            "all" => __('All Categories'),
            "active" => __('Active Categories'),
            "inactive" => __('Inactive Categories'),
            "with_products"=>__('With products'),
            "no_products"=>__('No products')
        ];
        $categoryGallery = CategoryGallery::all();
        return view('admin.category.index', compact('categoryGallery', 'filters'));
    }

    //*** GET Request
    public function create()
    {
        return view('admin.category.create');
    }

    //*** POST Request
    public function store(Request $request)
    {

        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'photo' => 'mimes:jpeg,jpg,png,svg,webp',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'slug' => 'unique:categories|regex:/^[a-zA-Z0-9\s-]+$/'
                 ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Category Name in :lang is required', ['lang' => $this->lang->language]),
            'photo.mimes' => __('Icon Type is Invalid.'),
            'banner' => __('Banner Type is Invalid.'),
            'slug.unique' => __('This slug has already been taken.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            if ($request->api) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends


        //--- Logic Section
        $data = new Category();
        $input = $this->removeEmptyTranslations($request->all());

        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/categories', $name);
            $input['photo'] = $name;
        }
        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/categories/banners', $name);
            $input['banner'] = $name;
        }

        //Customizable checkbutton
        if ($request->is_customizable == "") {
            $input['is_customizable'] = 0;
        } else {
            $input['is_customizable'] = 1;
        }

        //Customizable number checkbutton
        if ($request->is_customizable_number == "") {
            $input['is_customizable_number'] = 0;
        } else {
            $input['is_customizable_number'] = 1;
        }

        //Featured checkbutton
        if ($request->is_featured == "") {
            $input['is_featured'] = 0;
        } else {
            $input['is_featured'] = 1;
            //--- Validation Section
            $rules = [
                    'image' => 'required|mimes:jpeg,jpg,png,svg,webp'
                        ];
            $customs = [
                    'image.required' => __('Feature Image is required.'),
                    'image.mimes' => __('Feature Image Type is Invalid.')
                        ];
            $validator = Validator::make($request->all(), $rules, $customs);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            if ($file = $request->file('image')) {
                $name = time().$file->getClientOriginalName();
                $file->move('storage/images/categories', $name);
                $input['image'] = $name;
            }
        }

        $data->fill($input)->save();
        //--- Logic Section Ends

        // Add To Gallery If any
        $lastid = $data->id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                if (in_array($key, $request->galval)) {
                    $gallery = new CategoryGallery;
                    $name = time().$file->getClientOriginalName();
                    $file->move('storage/images/galleries', $name);
                    $gallery['photo'] = $name;
                    $gallery['category_id'] = $lastid;
                    $gallery->save();
                }
            }
        }

        //-----Creating automatic slug
        $cat = Category::find($data->id);
        $cat->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $cat->update();

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
        $data = Category::findOrFail($id);
        return view('admin.category.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'photo' => 'mimes:jpeg,jpg,png,svg,webp',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'slug' => 'unique:categories,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/',
            'link' => 'nullable',
                 ];

        $customs = [
            "{$this->lang->locale}.name.required" => __('Category Name in :lang is required', ['lang' => $this->lang->language]),
            'photo.mimes' => __('Icon Type is Invalid.'),
            'banner' => __('Banner Type is Invalid.'),
            'slug.unique' => __('This slug has already been taken.'),
            'slug.regex' => __('Slug Must Not Have Any Special Characters.')
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);       
        if ($validator->fails()) {
            if ($request->api) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Category::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);

        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/categories', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/storage/images/categories/'.$data->photo)) {
                    unlink(public_path().'/storage/images/categories/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }

        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/categories/banners', $name);
            if ($data->banner != null) {
                if (file_exists(public_path().'/storage/images/categories/banners/'.$data->banner) && !empty($data->banner)) {
                    unlink(public_path().'/storage/images/categories/banners/'.$data->banner);
                }
            }
            $input['banner'] = $name;
        }

        if ($request->is_customizable == "") {
            $input['is_customizable'] = 0;
        } else {
            $input['is_customizable'] = 1;
        }

        if ($request->is_customizable_number == "") {
            $input['is_customizable_number'] = 0;
        } else {
            $input['is_customizable_number'] = 1;
        }

        if ($request->is_featured == "") {
            $input['is_featured'] = 0;
        } else {
            $input['is_featured'] = 1;
            //--- Validation Section
            $rules = [
                        'image' => 'mimes:jpeg,jpg,png,svg,webp'
                            ];
            $customs = [
                        'image.required' => __('Feature Image is required.')
                            ];
            $validator = Validator::make($request->all(), $rules, $customs);

            if ($validator->fails()) {
                if ($request->api) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
                }
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            if ($file = $request->file('image')) {
                $name = time().$file->getClientOriginalName();
                $file->move('storage/images/categories', $name);
                $input['image'] = $name;
            }
        }

        $data->update($input);
        //----Slug automatic
        $data = Category::findOrFail($id);
        $data->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $data->update($input);

        //--- Logic Section Ends

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
        $data = Category::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        foreach ($data->products as $prod) {
            $prod->status = $id2;
            $prod->update();
        }
    }

    public function changeCatPos($id, $newPos)
    {
        $data = Category::findOrFail($id);
        $data->presentation_position = $newPos;
        $data->update();
    }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Category::findOrFail($id);

        if ($data->attributes->count() > 0) {
            Attribute::where('attributable_id', $id)->where('attributable_type', '=', 'App\Models\Category')->delete();
        }

        if ($data->subs->count() > 0) {
            $subId = $data->subs()->get()->pluck('id');
            Attribute::where('attributable_id', $subId)->where('attributable_type', '=', 'App\Models\Subcategory')->delete();
            $subs = Subcategory::where('id', $subId)->get();
            foreach ($subs as $sub) {
                foreach ($sub->childs as $child) {
                    $childId = $sub->childs()->get()->pluck('id');
                    Attribute::where('attributable_id', $childId)->where('attributable_type', '=', 'App\Models\Childcategory')->delete();
                }
            }
        }

        //If Photo Doesn't Exist
        if ($data->photo == null && $data->image == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/categories/'.$data->photo) && !empty($data->photo)) {
            unlink(public_path().'/storage/images/categories/'.$data->photo);
        }
        if (file_exists(public_path().'/storage/images/categories/'.$data->image) && !empty($data->image)) {
            unlink(public_path().'/storage/images/categories/'.$data->image);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function deleteImage(Request $request)
    {
        $data = Category::findOrFail($request->id);
        $path = public_path().'/storage/images/categories/'.$data->{$request->target};
        if (file_exists($path) && !empty($data->{$request->target})) {
            unlink($path);
            $data->{$request->target} = '';
            $data->update();
            $msg = __('Image Deleted Successfully');
            return response()->json([
                'status'=>true,
                'message' => $msg,
                'noimage' => 'url('. asset('assets/images/noimage.png') .')'
            ]);
        }
    }

    public function deleteBanner(Request $request)
    {
        $data = Category::findOrFail($request->id);
        $path = public_path().'/storage/images/categories/banners/'.$data->{$request->target};
        if (file_exists($path) && !empty($data->{$request->target})) {
            unlink($path);
            $data->{$request->target} = '';
            $data->update();
            $msg = __('Image Deleted Successfully');
            return response()->json([
                'status'=>true,
                'message' => $msg,
                'noimage' => 'url('. asset('assets/images/noimage.png') .')'
            ]);
        }
    }
}
