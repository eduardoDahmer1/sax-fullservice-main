<?php

namespace App\Http\Controllers\Admin;

use Image;
use Validator;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Product::where('product_type', '=', 'affiliate')->orderBy('id', 'desc');

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('name', function (Product $data) {
            $name = mb_strlen(strip_tags($data->name), 'utf-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'utf-8') . '...' : strip_tags($data->name);
            $id = '<small>' . __('Product ID') . ': <a href="' . route('front.product', $data->slug) . '" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';
            $id2 = $data->user_id != 0 ? (count($data->user->products) > 0 ? '<small class="ml-2"> ' . __('Vendor') . ': <a href="' . route('admin-vendor-show', $data->user_id) . '" target="_blank">' . $data->user->shop_name . '</a></small>' : '') : '';
            return  $name . '<br>' . $id . $id2;
        })
        ->editColumn('price', function (Product $data) {
            $sign = Currency::where('id', '=', 1)->first();
            $price = $sign->sign . $data->price;
            return  $price;
        })
        ->editColumn('stock', function (Product $data) {
            $stck = (string)$data->stock;
            if ($stck == "0")
                return __("Out Of Stock!");
            elseif ($stck == null)
                return __("Unlimited");
            else
                return $data->stock;
        })
        ->addColumn('status', function (Product $data) {
            $s = $data->status == 1 ? 'checked' : '';
            return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-prod-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
        })
       ->addColumn('action', function (Product $data) {
                return '<div class="godropdown"><button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-import-edit', $data->id) . '"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a><a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="' . $data->id . '"><i class="fas fa-eye"></i> ' . __('View Gallery') . '</a><a data-href="' . route('admin-prod-feature', $data->id) . '" class="feature" data-toggle="modal" data-target="#modal2" data-header="'.__('Highlight').'"> <i class="fas fa-star"></i> ' . __('Highlight') . '</a><a href="javascript:;" data-href="' . route('admin-affiliate-prod-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a></div></div>';
            })
        /*->addColumn('action', function (Product $data) {
                return '<div class="godropdown"><button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-import-edit', $data->id) . '"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a><a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="' . $data->id . '"><i class="fas fa-eye"></i> ' . __('View Gallery') . '</a><a data-href="' . route('admin-prod-feature', $data->id) . '" class="feature" data-toggle="modal" data-target="#modal2"> <i class="fas fa-star"></i> ' . __('Highlight') . '</a><a href="javascript:;" data-href="' . route('admin-affiliate-prod-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a></div></div>';
        })*/
        ->rawColumns(['name', 'status', 'action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.productimport.index');
    }

    //*** GET Request
    public function createImport()
    {
        $cats = Category::all();
        $sign = Currency::where('id','=',1)->first();
        $stores = Generalsetting::all();
        return view('admin.productimport.createone',compact('cats','sign','stores'));
    }

    //*** GET Request
    public function importCSV()
    {
        $cats = Category::all();
        $sign = Currency::where('id','=',1)->first();
        return view('admin.productimport.importcsv',compact('cats','sign'));
    }

    //*** POST Request
    public function uploadUpdate(Request $request,$id)
    {
        //--- Validation Section
        $rules = [
          'image' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = Product::findOrFail($id);

        //--- Validation Section Ends
        $image = $request->image;
        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name = time().Str::random(8).'.png';
        $path = 'storage/images/products/'.$image_name;
        file_put_contents($path, $image);
                if($data->photo != null)
                {
                    if (file_exists(public_path().'/storage/images/products/'.$data->photo)) {
                        unlink(public_path().'/storage/images/products/'.$data->photo);
                    }
                }
                        $input['photo'] = $image_name;
         $data->update($input);


        return response()->json(['status'=>true,'file_name' => $image_name]);
    }

    //*** POST Request
    public function store(Request $request)
    {
        if ($request->image_source == 'file') {
            //--- Validation Section
            $rules = [
                "{$this->lang->locale}.name" => 'required',
                'photo'      => 'required',
                'file'       => 'mimes:zip'
            ];
            $customs = [
                "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends

        }

        //--- Logic Section
        $data = new Product;
        $sign = Currency::where('id', '=', 1)->first();
        $input = $this->withRequiredFields($request->all(), ['name']);

        // Check File
        if ($file = $request->file('file')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('assets/files', $name);
            $input['file'] = $name;
        }

        $input['photo'] = "";
        if ($request->photo != "") {
            $image = $request->photo;
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name = time() . Str::random(8) . '.png';
            $path = 'storage/images/products/' . $image_name;
            file_put_contents($path, $image);
            $input['photo'] = $image_name;
        } else {
            $input['photo'] = $request->photolink;
        }

        //-- Translations Section
        // Will check each field in language 1 and then for each other language

        // Check Seo
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        //tags
        if (!empty($input[$this->lang->locale]['tags'])) {
            $input[$this->lang->locale]['tags'] = implode(',', $input[$this->lang->locale]['tags']);
        }

        // Check Features
        if (in_array(null, $input[$this->lang->locale]['features']) || in_array(null, $request->colors)) {
            $input[$this->lang->locale]['features'] = null;
            $input['colors'] = null;
        } else {
            $input[$this->lang->locale]['features'] = implode(
                ',',
                str_replace(',', ' ', $input[$this->lang->locale]['features'])
            );
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        }

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            if (!empty($input[$loc->locale]['meta_tag'])) {
                $input[$loc->locale]['meta_tag'] = implode(',', $input[$loc->locale]['meta_tag']);
            }

            if (!empty($input[$loc->locale]['tags'])) {
                $input[$loc->locale]['tags'] = implode(',', $input[$loc->locale]['tags']);
            }

            if (in_array(null, $input[$loc->locale]['features'])) {
                $input[$loc->locale]['features'] = null;
            } else {
                $input[$loc->locale]['features'] = implode(
                    ',',
                    str_replace(',', ' ', $input[$loc->locale]['features'])
                );
            }
        }
        //-- End Translations Section

        // Check Physical
        if ($request->type == "Physical") {

            //--- Validation Section
            $rules = ['sku'      => 'min:8|unique:products'];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends



            // Check Condition
            if ($request->product_condition_check == "") {
                $input['product_condition'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == "") {
                $input['ship'] = null;
            }

            // Check Size
            if (empty($request->size_check)) {
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
            } else {
                if (in_array(null, $request->size) || in_array(null, $request->size_qty)) {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                } else {
                    $input['size'] = implode(',', $request->size);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $input['size_price'] = implode(',', $request->size_price);
                }
            }

            // Check Color
            if (empty($request->color_check)) {
                $input['color'] = null;
            } else {
                $input['color'] = implode(',', $request->color);
            }

            // Check Measurement
            if ($request->mesasure_check == "") {
                $input['measure'] = null;
            }
        }

        // Check License

        if ($request->type == "License") {

            if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                $input['license'] = null;
                $input['license_qty'] = null;
            } else {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            }
        }

        // Conert Price According to Currency
        $input['price'] = ($input['price'] / $sign->value);
        $input['previous_price'] = ($input['previous_price'] / $sign->value);
        $input['product_type'] = "affiliate";

        // Save Data
        $data->fill($input)->save();

        // Set SLug
        $prod = Product::find($data->id);
        if ($prod->type != 'Physical') {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower(Str::random(3) . $data->id . Str::random(3));
        } else {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);
        }
        $fimageData = public_path() . '/storage/images/products/' . $prod->photo;
        if (filter_var($prod->photo, FILTER_VALIDATE_URL)) {
            $fimageData = $prod->photo;
        }

        $img = Image::make($fimageData)->resize(285, 285);
        $thumbnail = time() . Str::random(8) . '.jpg';
        $img->save(public_path() . '/storage/images/thumbnails/' . $thumbnail);
        $prod->thumbnail  = $thumbnail;
        $prod->update();

        // Add To Gallery If any
        $lastid = $data->id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                if (in_array($key, $request->galval)) {
                    $gallery = new Gallery;
                    $name = time() . $file->getClientOriginalName();
                    $img = Image::make($file->getRealPath())->resize(800, 800);
                    $thumbnail = time() . Str::random(8) . '.jpg';
                    $img->save(public_path() . '/storage/images/galleries/' . $name);
                    $gallery['photo'] = $name;
                    $gallery['product_id'] = $lastid;
                    $gallery->save();
                }
            }
        }
        //logic Section Ends

        //associates with stores
        if($request->has('stores')) {
            $prod->stores()->sync($input['stores']);
        }

        //--- Redirect Section
        $msg = __('New Affiliate Product Added Successfully.') . '<a href="' . route('admin-import-index') . '">' . __('View Product Lists.') . '</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $cats = Category::all();
        $data = Product::findOrFail($id);
        $sign = Currency::where('id','=',1)->first();
        $stores = Generalsetting::all();
        $currentStores = $data->stores()->pluck('id')->toArray();
        return view('admin.productimport.editone',compact('cats','data','sign','stores','currentStores'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        $prod = Product::find($id);
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'file'       => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends


        //-- Logic Section
        $data = Product::findOrFail($id);
        $sign = Currency::where('id', '=', 1)->first();
        $input = $this->withRequiredFields($request->all(), ['name']);

        //-- Translations Section
        // Will check each field in language 1 and then for each other language

        // Check Seo
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        //Product Tags
        if (!empty($input[$this->lang->locale]['tags'])) {
            $input[$this->lang->locale]['tags'] = implode(',', $input[$this->lang->locale]['tags']);
        }

        // Check Features
        if (!in_array(null, $input[$this->lang->locale]['features']) && !in_array(null, $request->colors)) {
            $input[$this->lang->locale]['features'] = implode(
                ',',
                str_replace(',', ' ', $input[$this->lang->locale]['features'])
            );
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        } else {
            if (in_array(null, $input[$this->lang->locale]['features']) || in_array(null, $request->colors)) {
                $input[$this->lang->locale]['features'] = null;
                $input['colors'] = null;
            } else {
                $features = explode(',', $data->features);
                $colors = explode(',', $data->colors);
                $input[$this->lang->locale]['features'] = implode(',', $features);
                $input['colors'] = implode(',', $colors);
            }
        }

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            if (!empty($input[$loc->locale]['meta_tag'])) {
                $input[$loc->locale]['meta_tag'] = implode(',', $input[$loc->locale]['meta_tag']);
            }

            if (!empty($input[$loc->locale]['tags'])) {
                $input[$loc->locale]['tags'] = implode(',', $input[$loc->locale]['tags']);
            }

            if (!in_array(null, $input[$loc->locale]['features'])) {
                $input[$loc->locale]['features'] = implode(',', str_replace(',', ' ', $input[$loc->locale]['features']));
            } else {
                if (in_array(null, $input[$loc->locale]['features'])) {
                    $input[$loc->locale]['features'] = null;
                } else {
                    $features = explode(',', $data->translate($loc->locale)->features);
                    $input[$loc->locale]['features'] = implode(',', $features);
                }
            }
        }
        //-- End of Translations Section

        //Check Types
        if ($request->type_check == 1) {
            $input['link'] = null;
        } else {
            if ($data->file != null) {
                if (file_exists(public_path() . '/assets/files/' . $data->file)) {
                    unlink(public_path() . '/assets/files/' . $data->file);
                }
            }
            $input['file'] = null;
        }

        if ($request->image_source == 'file') {
            $input['photo'] = $request->photo;
        } else {
            $input['photo'] = $request->photolink;
        }


        // Check Physical
        if ($data->type == "Physical") {

            //--- Validation Section
            $rules = ['sku' => 'min:1|unique:products,sku,' . $id];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends


            // Check Condition
            if ($request->product_condition_check == "") {
                $input['product_condition'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == "") {
                $input['ship'] = null;
            }

            // Check Size

            if (empty($request->size_check)) {
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
            } else {
                if (in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price)) {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                } else {
                    $input['size'] = implode(',', $request->size);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $input['size_price'] = implode(',', $request->size_price);
                }
            }

            // Check Color
            if (empty($request->color_check)) {
                $input['color'] = null;
            } else {
                if (!empty($request->color)) {
                    $input['color'] = implode(',', $request->color);
                }
                if (empty($request->color)) {
                    $input['color'] = null;
                }
            }

            // Check Measure
            if ($request->measure_check == "") {
                $input['measure'] = null;
            }
        }

        // Check License
        if ($data->type == "License") {

            if (!in_array(null, $request->license) && !in_array(null, $request->license_qty)) {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            } else {
                if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                } else {
                    $license = explode(',,', $prod->license);
                    $license_qty = explode(',', $prod->license_qty);
                    $input['license'] = implode(',,', $license);
                    $input['license_qty'] = implode(',', $license_qty);
                }
            }
        }

        $input['price'] = $input['price'] / $sign->value;
        $input['previous_price'] = $input['previous_price'] / $sign->value;

        $data->slug = Str::slug($data->name, '-') . '-' . strtolower($data->sku);
        $data->update($input);

        //associates with stores
        $data->stores()->detach();
        if ($request->has('stores')) {
            $data->stores()->sync($input['stores']);
        }

        //-- Logic Section Ends

        if ($data->photo != null) {
            if (file_exists(public_path() . '/storage/images/thumbnails/' . $data->thumbnail)) {
                unlink(public_path() . '/storage/images/thumbnails/' . $data->thumbnail);
            }
        }

        $fimageData = public_path() . '/storage/images/products/' . $prod->photo;

        if (filter_var($prod->photo, FILTER_VALIDATE_URL)) {
            $fimageData = $prod->photo;
        }

        $img = Image::make($fimageData)->resize(285, 285);
        $thumbnail = time() . Str::random(8) . '.jpg';
        $img->save(public_path() . '/storage/images/thumbnails/' . $thumbnail);
        $prod->thumbnail  = $thumbnail;
        $prod->update();

        //--- Redirect Section
        $msg = __('Product Updated Successfully.') . '<a href="' . route('admin-import-index') . '">' . __('View Product Lists.') . '</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
