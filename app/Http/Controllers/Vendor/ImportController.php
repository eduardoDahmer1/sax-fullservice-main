<?php

namespace App\Http\Controllers\Vendor;

use DB;
use Auth;
use Image;
use Validator;
use Datatables;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Attribute;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\Generalsetting;
use App\Models\AttributeOption;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ImportController extends Controller
{
    public $global_language;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();
            if($user->checkWarning())
            {
                return redirect()->route('vendor-warning',$user->verifies()->where('admin_warning','=','1')->orderBy('id','desc')->first()->id);
            }
            if(!$user->checkStatus())
            {
                return redirect()->route('vendor-verify');
            }
            return $next($request);
        });
    }

    //*** JSON Request
    public function datatables()
    {
         $user = Auth::user();
         $datas = $user->products()->where('product_type','affiliate')->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->addColumn('action', function (Product $data) {
            return '
            <div class="godropdown">
                <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                <div class="action-list"><a href="' . route('vendor-import-edit', $data->id) . '"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                    <a href="javascript" data-header="'.__("Image Gallery").'" class="set-gallery-product" data-toggle="modal" data-target="#setgallery">
                    <input type="hidden" value="' . $data->id . '"><i class="fas fa-eye"></i> ' . __('View Gallery') . '</a>
                    <a href="javascript:;" data-href="' . route('vendor-prod-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                </div>
            </div>';
        })
        ->editColumn('photo', function (Product $data) {
            if (file_exists(public_path().'/storage/images/thumbnails/'.$data->thumbnail)) {
                return asset('storage/images/thumbnails/'.$data->thumbnail);
            } else{
                return asset('assets/images/noimage.png');
            }
        })
        ->editColumn('name', function(Product $data) {
            $name = mb_strlen(strip_tags($data->name),'utf-8') > 50 ? mb_substr(strip_tags($data->name),0,50,'utf-8').'...' : strip_tags($data->name);
            $id = '<small>Product ID: <a href="'.route('front.product', $data->slug).'" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';
            return  $name.'<br>'.$id;
        })
        ->editColumn('stock', function (Product $data) {
            $stck = (string)$data->stock;
            if ($stck == "0")
                return __("Out Of Stock!");
            elseif ($stck == null)
                return __("Unlimited");
                else return $data->stock;
        })
        ->editColumn('price', function(Product $data) {
            $sign = $curr = Currency::find($this->storeSettings->currency_id);
            $price = $sign->sign.$data->price;
            return  $price;
        })
        ->addColumn('status', function (Product $data) {
            $s = $data->status == 1 ? 'checked' : '';
            return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('vendor-prod-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
        })
        ->rawColumns(['name', 'status', 'action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('vendor.productimport.index');
    }

    //*** GET Request
    public function createImport()
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = $curr = Currency::find($this->storeSettings->currency_id);
        $storesList = Generalsetting::all();
        return view('vendor.productimport.createone',compact('cats','sign','brands','storesList'));
    }

    //*** GET Request
    public function importCSV()
    {
        $cats = Category::all();
        $sign = $curr = Currency::find($this->storeSettings->currency_id);
        return view('vendor.productimport.importcsv',compact('cats','sign'));
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
         if($data->thumbnail != null)
                {
                    if (file_exists(public_path().'/storage/images/thumbnails/'.$data->thumbnail)) {
                        unlink(public_path().'/storage/images/thumbnails/'.$data->thumbnail);
                    }
                }

        $img = Image::make(public_path().'/storage/images/products/'.$data->photo)->resize(285, 285);
        $thumbnail = time().Str::random(8).'.png';
        $img->save(public_path().'/storage/images/thumbnails/'.$thumbnail);
        $data->thumbnail  = $thumbnail;
        $data->update();


        return response()->json(['status'=>true,'file_name' => $image_name]);
    }

    //*** POST Request
    public function store(Request $request)
    {
        $user = Auth::user();
        $package = $user->subscribes()->orderBy('id','desc')->first();
        $prods = $user->products()->orderBy('id','desc')->get()->count();

        if($prods < $package->allowed_products || $package->allowed_products == 0)
        {

            if($request->image_source == 'file')
            {
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
            $sign = Currency::find($this->storeSettings->currency_id);
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
            if($request->type == "Physical")
            {
                    //--- Validation Section
                    $rules = ['sku'      => 'min:8|unique:products'];

                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    }
                    //--- Validation Section Ends


            // Check Condition
            if ($request->product_condition_check == ""){
                $input['product_condition'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == ""){
                $input['ship'] = null;
            }

            // Check Size
            if(empty($request->size_check ))
            {
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
            }
            else{
                if(in_array(null, $request->size) || in_array(null, $request->size_qty))
                {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                }
                else
                {
                    foreach($request->size as $key => $size){
                        $size_without_comma[$key] = str_replace(',', '.', $size);
                    }
                    $input['size'] = implode(',', $size_without_comma);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $input['size_price'] = implode(',', $request->size_price);
                    $stck = 0;
                    foreach($request->size_qty as $key => $size){
                        $stck += (int)$request->size_qty[$key];
                    }
                    $input['stock'] = $stck;
                }
            }

            // Check Color
            if(empty($request->color_check ))
            {
                $input['color'] = null;
                $input['color_qty'] = null;
                $input['color_price'] = null;
            }
            else{
                if(in_array(null, $request->color) || in_array(null, $request->color_qty))
                {
                    $input['color'] = null;
                    $input['color_qty'] = null;
                    $input['color_price'] = null;
                }
                else
                {
                    $input['color'] = implode(',', $request->color);
                    $input['color_qty'] = implode(',', $request->color_qty);
                    $input['color_price'] = implode(',', $request->color_price);
                    $stck = 0;
                    foreach($request->color_qty as $key => $color){
                        $stck += (int)$request->color_qty[$key];
                    }
                    $input['stock'] = $stck;
                }
            }

            // Check Measurement
            if ($request->mesasure_check == "")
             {
                $input['measure'] = null;
             }

            }

             // Check License

            if($request->type == "License")
            {

                if(in_array(null, $request->license) || in_array(null, $request->license_qty))
                {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                }
                else
                {
                    $input['license'] = implode(',,', $request->license);
                    $input['license_qty'] = implode(',', $request->license_qty);
                }

            }

            // Conert Price According to Currency
             $input['price'] = ($input['price'] / $sign->value);
             $input['previous_price'] = ($input['previous_price'] / $sign->value);
             $input['product_type'] = "affiliate";
             $input['user_id'] = Auth::user()->id;
              // store filtering attributes for physical product
        $attrArr = [];
        if (!empty($request->category_id)) {
          $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
          if (!empty($catAttrs)) {
            foreach ($catAttrs as $key => $catAttr) {
              $in_name = $catAttr->input_name;
              if ($request->has("attr_"."$in_name")) {
                $attrArr["$in_name"]["values"] = $request["attr_"."$in_name"];
                $attrArr["$in_name"]["prices"] = $request["attr_"."$in_name"."_price"];
                if ($catAttr->details_status) {
                  $attrArr["$in_name"]["details_status"] = 1;
                } else {
                  $attrArr["$in_name"]["details_status"] = 0;
                }
              }
            }
          }
        }

        if (!empty($request->subcategory_id)) {
            $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
            if (!empty($subAttrs)) {
              foreach ($subAttrs as $key => $subAttr) {
                $in_name = $subAttr->input_name;
                if ($request->has("attr_"."$in_name")) {
                  $attrArr["$in_name"]["values"] = $request["attr_"."$in_name"];
                  $attrArr["$in_name"]["prices"] = $request["attr_"."$in_name"."_price"];
                  if ($subAttr->details_status) {
                    $attrArr["$in_name"]["details_status"] = 1;
                  } else {
                    $attrArr["$in_name"]["details_status"] = 0;
                  }
                }
              }
            }
          }

          if (!empty($request->childcategory_id)) {
          $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
          if (!empty($childAttrs)) {
            foreach ($childAttrs as $key => $childAttr) {
              $in_name = $childAttr->input_name;
              if ($request->has("attr_"."$in_name")) {
                $attrArr["$in_name"]["values"] = $request["attr_"."$in_name"];
                $attrArr["$in_name"]["prices"] = $request["attr_"."$in_name"."_price"];
                if ($childAttr->details_status) {
                  $attrArr["$in_name"]["details_status"] = 1;
                } else {
                  $attrArr["$in_name"]["details_status"] = 0;
                }
              }
            }
          }
        }



           if (empty($attrArr)) {
             $input['attributes'] = NULL;
           } else {
             $jsonAttr = json_encode($attrArr);
             $input['attributes'] = $jsonAttr;
           }
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
            if ($files = $request->file('gallery')){
                foreach ($files as  $key => $file){
                    if(in_array($key, $request->galval))
                    {
                        $gallery = new Gallery;
                        $name = time().$file->getClientOriginalName();
                        $file->move('storage/images/galleries',$name);
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
        $msg = __('New Affiliate Product Added Successfully.').'<a href="'.route('vendor-import-index').'">'.__('View Product Lists.').'</a>';
        return response()->json($msg);
        //--- Redirect Section Ends
        }
        else
        {
        //--- Redirect Section
        return response()->json(array('errors' => [ 0 => __('You Canot Add More Product.')]));

        //--- Redirect Section Ends
        }

    }

    //*** GET Request
    public function edit($id)
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $data = Product::findOrFail($id);
        if(Auth::user()->id == $data->user_id){
            $selectedAttrs = json_decode($data->attributes, true);
            $catAttributes = !empty($data->category->attributes) ? $data->category->attributes : '';
            $subAttributes = !empty($data->subcategory->attributes) ? $data->subcategory->attributes : '';
            $childAttributes = !empty($data->childcategory->attributes) ? $data->childcategory->attributes : '';
            $sign = Currency::where('id','=',1)->first();
            $storesList = Generalsetting::all();
            $currentStores = $data->stores()->pluck('id')->toArray();
        return view('vendor.productimport.editone',compact('cats','data','selectedAttrs','catAttributes','childAttributes',
        'subAttributes','sign','brands','storesList','currentStores'));
        } else{
            return redirect()->route('vendor-prod-index');
        }
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        $prod = Product::find($id);
        //--- Validation Section
        if($request->image_source == 'file')
        {

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


        }


        //-- Logic Section
        $data = Product::findOrFail($id);
        $sign = Currency::find(1);
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
            if($request->type_check == 1)
            {
                $input['link'] = null;
            }
            else
            {
                if($data->file!=null){
                        if (file_exists(public_path().'/assets/files/'.$data->file)) {
                        unlink(public_path().'/assets/files/'.$data->file);
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
            if($data->type == "Physical")
            {
                    //--- Validation Section
                    $rules = ['sku' => 'min:1|unique:products,sku,'.$id];

                    $validator = Validator::make($request->all(), $rules);

                    if ($validator->fails()) {
                        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
                    }
                    //--- Validation Section Ends

                        // Check Condition
                        if ($request->product_condition_check == ""){
                            $input['product_condition'] = 0;
                        }

                        // Check Shipping Time
                        if ($request->shipping_time_check == ""){
                            $input['ship'] = null;
                        }

                        // Check Size

                        if(empty($request->size_check ))
                        {
                            $input['size'] = null;
                            $input['size_qty'] = null;
                            $input['size_price'] = null;
                        }
                        else{
                                if(in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price))
                                {
                                    $input['size'] = null;
                                    $input['size_qty'] = null;
                                    $input['size_price'] = null;
                                }
                                else
                                {
                                    foreach($request->size as $key => $size){
                                        $size_without_comma[$key] = str_replace(',', '.', $size);
                                    }
                                    $input['size'] = implode(',', $size_without_comma);
                                    $input['size_qty'] = implode(',', $request->size_qty);
                                    $input['size_price'] = implode(',', $request->size_price);
                                    $stck = 0;
                                    foreach($request->size_qty as $key => $size){
                                        $stck += (int)$request->size_qty[$key];
                                    }
                                    $input['stock'] = $stck;
                                }
                        }

                        // Check Color
                        if(empty($request->color_check ))
                        {
                            $input['color'] = null;
                            $input['color_qty'] = null;
                            $input['color_price'] = null;
                        }
                        else{
                            if(in_array(null, $request->color) || in_array(null, $request->color_qty))
                            {
                                $input['color'] = null;
                                $input['color_qty'] = null;
                                $input['color_price'] = null;
                            }
                            else
                            {
                                $input['color'] = implode(',', $request->color);
                                $input['color_qty'] = implode(',', $request->color_qty);
                                $input['color_price'] = implode(',', $request->color_price);
                                $stck = 0;
                                foreach($request->color_qty as $key => $color){
                                    $stck += (int)$request->color_qty[$key];
                                }
                                $input['stock'] = $stck;
                            }
                        }

                        // Check Measure
                    if ($request->measure_check == "")
                     {
                        $input['measure'] = null;
                     }
            }

        // Check License
        if($data->type == "License")
        {

        if(!in_array(null, $request->license) && !in_array(null, $request->license_qty))
        {
            $input['license'] = implode(',,', $request->license);
            $input['license_qty'] = implode(',', $request->license_qty);
        }
        else
        {
            if(in_array(null, $request->license) || in_array(null, $request->license_qty))
            {
                $input['license'] = null;
                $input['license_qty'] = null;
            }
            else
            {
                $license = explode(',,', $prod->license);
                $license_qty = explode(',', $prod->license_qty);
                $input['license'] = implode(',,', $license);
                $input['license_qty'] = implode(',', $license_qty);
            }
        }

        }

         $input['price'] = $input['price'] / $sign->value;
         $input['previous_price'] = $input['previous_price'] / $sign->value;

         // store filtering attributes for physical product
         $attrArr = [];
         if (!empty($request->category_id)) {
           $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
           if (!empty($catAttrs)) {
             foreach ($catAttrs as $key => $catAttr) {
               $in_name = $catAttr->input_name;
               if ($request->has("attr_"."$in_name")) {
                 $attrArr["$in_name"]["values"] = $request["attr_"."$in_name"];
                 $attrArr["$in_name"]["prices"] = $request["attr_"."$in_name"."_price"];
                 if ($catAttr->details_status) {
                   $attrArr["$in_name"]["details_status"] = 1;
                 } else {
                   $attrArr["$in_name"]["details_status"] = 0;
                 }
               }
             }
           }
         }

         if (!empty($request->subcategory_id)) {
           $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
           if (!empty($subAttrs)) {
             foreach ($subAttrs as $key => $subAttr) {
               $in_name = $subAttr->input_name;
               if ($request->has("attr_"."$in_name")) {
                 $attrArr["$in_name"]["values"] = $request["attr_"."$in_name"];
                 $attrArr["$in_name"]["prices"] = $request["attr_"."$in_name"."_price"];
                 if ($subAttr->details_status) {
                   $attrArr["$in_name"]["details_status"] = 1;
                 } else {
                   $attrArr["$in_name"]["details_status"] = 0;
                 }
               }
             }
           }
         }
         if (!empty($request->childcategory_id)) {
           $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
           if (!empty($childAttrs)) {
             foreach ($childAttrs as $key => $childAttr) {
               $in_name = $childAttr->input_name;
               if ($request->has("attr_"."$in_name")) {
                 $attrArr["$in_name"]["values"] = $request["attr_"."$in_name"];
                 $attrArr["$in_name"]["prices"] = $request["attr_"."$in_name"."_price"];
                 if ($childAttr->details_status) {
                   $attrArr["$in_name"]["details_status"] = 1;
                 } else {
                   $attrArr["$in_name"]["details_status"] = 0;
                 }
               }
             }
           }
         }

         if (empty($attrArr)) {
           $input['attributes'] = NULL;
         } else {
           $jsonAttr = json_encode($attrArr);
           $input['attributes'] = $jsonAttr;
         }
         $data->update($input);
            $data = Product::findOrFail($id);

            if($data->type != 'Physical'){
                $data->slug = Str::slug($data->name,'-').'-'.strtolower(Str::random(3).$data->id.Str::random(3));
            }
            else {
                $data->slug = Str::slug($data->name,'-').'-'.strtolower(Str::slug($data->sku));
            }
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
        $msg = __('Product Updated Successfully.') . '<a href="' . route('vendor-import-index') . '">' . __('View Product Lists.') . '</a>';
        return response()->json($msg);
        //--- Redirect Section Ends

    }
}
