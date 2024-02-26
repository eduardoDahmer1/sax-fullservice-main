<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
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
        $datas = Brand::orderBy('id', 'asc');
        //--- Integrating This Collection Into Datatables
        if ($filter == 'active') {
            $datas = $datas->active()->orderBy('id', 'asc');
        } elseif ($filter == 'inactive') {
            $datas = $datas->inactive()->orderBy('id', 'asc');
        } elseif ($filter == 'with_products') {
            $datas = $datas->withProducts()->orderBy('id', 'asc');
        } elseif ($filter == 'no_products') {
            $datas = $datas->withoutProducts()->orderBy('id', 'asc');
        }
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('status', function (Brand $data) {
                $s = $data->status == 1 ? 'checked' : '';
                //return '<div class="action-list"><select class="process select droplinks ' . $class . '"><option data-val="1" value="' . route('admin-brand-status', ['id1' => $data->id, 'id2' => 1]) . '" ' . $s . '>' . __('Activated') . '</option><option data-val="0" value="' . route('admin-brand-status', ['id1' => $data->id, 'id2' => 0]) . '" ' . $ns . '>' . __('Deactivated') . '</option>/select></div>';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-brand-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->editColumn('name', function (Brand $data) {
                return $data->name . '<br><small>slug: '.$data->slug.'</small>';
            })
            ->addColumn('products', function (Brand $data) {
                $buttons = __('None');
                if ($data->products()->where('status', 1)->count() > 0) {
                    $buttons = '<div class="action-list">';
                    $buttons .= '<a href="' . route('front.brand', $data->slug) . '" target="_blank"> <i class="fas fa-external-link-alt"></i>' . __('Products') . '</a>';
                    $buttons .= '</div>';
                }

                return $buttons;
            })
            ->addColumn('action', function (Brand $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-brand-edit', $data->id) . '" data-header="' . __('Edit Brand') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit Brand') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-brand-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                    </div>
                </div>';
            })
            ->rawColumns(['status', 'name', 'slug', 'products', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }


      //*** GET Request
      public function index()
      {
          $filters = [
              "all" => __('All Brands'),
              "active" => __('Active Brands'),
              "inactive" => __('Inactive Brands'),
              "with_products" => __('With Products'),
              "no_products" => __('No Products'),
          ];
        
          return view('admin.brand.index', compact('filters'));
      } 

    //*** GET Request
    public function create()
    {
        return view('admin.brand.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'image' => 'mimes:jpeg,jpg,png,svg,webp',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'name' => 'unique:brands|required',
            'slug' => 'unique:brands|regex:/^[a-zA-Z0-9\s-]+$/'
        ];
        $customs = [
            'name.unique' => __('This name has already been taken.'),
            'image.mimes' => __('Image Type is Invalid.'),
            'banner.mimes' => __('Banner Type is Invalid.'),
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
        $data = new Brand();
        $input = $request->all();
        if ($file = $request->file('image')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/brands', $name);
            $input['image'] = $name;
        }
        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/brands/banners', $name);
            $input['banner'] = $name;
        }
        if ($request->partner == "") {
            $input['partner'] = 0;
        }
        $data->fill($input)->save();
        //--- Logic Section Ends

        //-----Creating automatic slug
        $brand = Brand::find($data->id);
        $brand->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $brand->update();

        //--- Redirect Section
        if ($request->api) {
            return response()->json(array('status' => 'ok'), Response::HTTP_CREATED);
        }

        if (!empty($input['image'])) {
            // Set Thumbnail
            $img = Image::make(public_path().'/storage/images/brands/'.$input['image'])->resize(null, 285, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path().'/storage/images/thumbnails/'.$input['image']);
            $data->thumbnail  = $input['image'];
            $data->update();
        }

        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'image' => 'mimes:jpeg,jpg,png,svg,webp',
            'banner' => 'mimes:jpeg,jpg,png,svg,webp',
            'name' => 'unique:brands,name,'.$id.'|required',
            'slug' => 'unique:brands,slug,'.$id.'|regex:/^[a-zA-Z0-9\s-]+$/'
        ];
        $customs = [
            'name.unique' => __('This name has already been taken.'),
            'image.mimes' => __('Image Type is Invalid.'),
            'banner.mimes' => __('Banner Type is Invalid.'),
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
        $data = Brand::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('image')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/brands', $name);
            if ($data->image != null) {
                if (file_exists(public_path().'/storage/images/brands/'.$data->image) && !empty($data->image)) {
                    unlink(public_path().'/storage/images/brands/'.$data->image);
                }
            }
            $input['image'] = $name;
        }

        if ($banner = $request->file('banner')) {
            $name = Str::random(8).time().".".$banner->getClientOriginalExtension();
            $banner->move('storage/images/brands/banners', $name);
            if ($data->banner != null) {
                if (file_exists(public_path().'/storage/images/brands/banners/'.$data->banner) && !empty($data->banner)) {
                    unlink(public_path().'/storage/images/brands/banners/'.$data->banner);
                }
            }
            $input['banner'] = $name;
        }

        if ($request->partner == "") {
            $input['partner'] = 0;
        }

        $data->update($input);
        //--- Logic Section Ends

        //----Slug automatic
        $data = Brand::findOrFail($id);
        $data->slug = Str::slug($data->name, '-').'-'.strtolower($data->id);
        $data->update($input);

        //--- Redirect Section
        if ($request->api) {
            return response()->json(array('status' => 'ok'), Response::HTTP_CREATED);
        }

        if (!empty($input['image'])) {
            // Set Thumbnail
            $img = Image::make(public_path().'/storage/images/brands/'.$input['image'])->resize(null, 285, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path().'/storage/images/thumbnails/'.$input['image']);
            $data->thumbnail  = $input['image'];
            $data->update();
        }

        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Status
    public function status($id1, $id2)
    {
        $data = Brand::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Brand::findOrFail($id);

        //If Image Doesn't Exist
        if ($data->image == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Image Exist
        if (file_exists(public_path().'/storage/images/brands/'.$data->image) && !empty($data->image)) {
            unlink(public_path().'/storage/images/brands/'.$data->image);
            //If Thumbnail Exist
            if (file_exists(public_path().'/storage/images/thumbnails/'.$data->thumbnail) && !empty($data->thumbnail)) {
                unlink(public_path().'/storage/images/thumbnails/'.$data->thumbnail);
            }
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function generateThumbnails()
    {
        $updated = 0;
        $brands = Brand::where('status', '=', 1)->get();
        foreach ($brands as $brand) {
            $thumb_path = public_path('storage/images/thumbnails/');
            if (!file_exists($thumb_path.$brand->image)) {
                $brand->thumbnail = $brand->image;
                if ($brand->update()) {
                    $img_dir = public_path().'/storage/images/brands/'.$brand->image;
                    $thumb_path .= $brand->image;
                    $img = Image::make($img_dir);
                    $thumb = $img->resize(null, 285, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $thumb->save($thumb_path);
                    $updated++;
                }
            }
        }
        if ($updated > 0) {
            $msg = __('Thumbnails successfully updated!');
            $alert = false;
        } else {
            $msg = __('There is no thumbnails to update!');
            $alert = true;
        }
        return response()->json(['status' => true, 'message' => $msg, 'alert' => $alert]);
    }

    public function deleteImage(Request $request)
    {
        $data = Brand::findOrFail($request->id);
        $path = public_path().'/storage/images/brands/'.$data->{$request->target};
        $thumbnail_path = public_path().'/storage/images/thumbnails/';
        if (file_exists($path) && !empty($data->{$request->target})) {
            unlink($path);
            $data->{$request->target} = '';
            if ($data->thumbnail && file_exists($thumbnail_path.$data->thumbnail)) {
                unlink($thumbnail_path.$data->thumbnail);
                $data->thumbnail = null;
            }
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
