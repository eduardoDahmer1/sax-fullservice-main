<?php

namespace App\Http\Controllers\Vendor;

use DB;
use Auth;
use Image;
use Validator;
use Datatables;
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

class ProductController extends Controller
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
    public function datatablesAdmin()
    {
         $datas = Product::where('user_id', 0)->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
         ->addColumn('action', function (Product $data) {
            return '
                <div class="action-list">
                    <a href="javascript:;" data-header="'.__("Sell"). " " . $data->name.'" data-href="' . route('vendor-prod-copy',$data->id) . '" class="copyproduct"><i class="fas fa-store"></i> ' . __('Sell') . '</a>
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
            $name = strlen(strip_tags($data->name)) > 50 ? substr(strip_tags($data->name),0,50).'...' : strip_tags($data->name);
            $id = '<small>ID: <a href="'.route('front.product', $data->slug).'" target="_blank">'.sprintf("%'.08d",$data->id).'</a></small>';
            return  $name.'<br>'.$id;
        })
        ->editColumn('price', function(Product $data) {
            $sign = Currency::find($this->storeSettings->currency_id);
            $price = round($data->price * $sign->value , 2);
            $price = $sign->sign.$price ;
            return  $price;
        })
        ->rawColumns(['name', 'status', 'action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** JSON Request
    public function datatables()
    {
    	 $user = Auth::user();
         $datas = $user->products()->where('product_type','normal')->orderBy('id','desc')->get();

         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
         ->addColumn('action', function (Product $data) {
            return '
            <div class="godropdown">
                <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                <div class="action-list">
                    <a href="javascript:;" data-href="' . route('vendor-prod-fastedit', $data->id) . '" data-toggle="modal" data-target="#fast_edit_modal" class="fasteditbtn"><i class="fas fa-edit"></i> ' . __('Edit') . '</a>
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
        ->editColumn('name', function (Product $data) {
            $this->useStoreLocale();
            $name = mb_strlen(strip_tags($data->name), 'utf-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'utf-8') . '...' : strip_tags($data->name);
            $id = '<small class="ml-2"> ' . __('REF CODE') . ': ' . $data->ref_code . '</small>';
            $fast_edit_btn = '<a title="'.__("Edit").'" data-href="' . route('vendor-prod-fastedit',$data->id) . '" class="fasteditbtn" data-header="'.__("Edit")." ".$data->ref_code.'" data-toggle="modal" data-target="#fast_edit_modal"><i class="fas fa-edit text-primary"></i></a>';
            $this->useAdminLocale();
            return  $fast_edit_btn.$name . '<br>' . $id;
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
            $sign = Currency::find($this->storeSettings->currency_id);
            $price = round($data->price * $sign->value , 2);
            $price = $sign->sign.$price ;
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
        return view('vendor.product.index');
    }

    //*** GET Request
    public function types()
    {
        return view('vendor.product.types');
    }

    //*** GET Request
    public function createPhysical()
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::where('id','=',1)->first();
        $storesList = Generalsetting::all();
        return view('vendor.product.create.physical',compact('cats','sign','brands','storesList'));
    }

    //*** GET Request
    public function createDigital()
    {
        $cats = Category::all();
        $sign = $curr = Currency::find($this->storeSettings->currency_id);
        return view('vendor.product.create.digital',compact('cats','sign'));
    }

    //*** GET Request
    public function createLicense()
    {
        $cats = Category::all();
        $sign = $curr = Currency::find($this->storeSettings->currency_id);
        return view('vendor.product.create.license',compact('cats','sign'));
    }

    //*** GET Request
    public function fastedit($id)
    {
        $data = Product::findOrFail($id);
        $sign = Currency::where('id','=',1)->first();
        return view('vendor.product.fastedit',compact('data','sign'));
    }

    //*** POST Request
    public function fasteditsubmit(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'price'       => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $input = $request->all();

        //-- Logic Section
        $data = Product::findOrFail($id);

        // Remove base product info
        $data->being_sold = false;
        $data->vendor_min_price = 0;
        $data->vendor_max_price = 0;

        $data->update($input);

        // Find admin product
        $base_prod = Product::where('user_id', 0)->where('slug', $data->slug)->first();
        $max = $base_prod->vendor_max_price;
        $min = $base_prod->vendor_min_price;

        // For each vendor product, works with its price
        foreach(Product::where('user_id', '!=', 0)->where('slug', $data->slug)->get() as $v_prod){
            // Price higher than max price
            if($v_prod->price >= $max){
                $max = $v_prod->price;
            }
            // Price lower than min price
            if($v_prod->price <= $min){
                $min = $v_prod->price;
            }
            // Price between both but there's no such price at Database (just set current price as min/max)
            // Prevents an old price to be related with admin product if there's no Vendor product with that price anymore.
            if($v_prod->price >= $min && $v_prod->price <= $max){
                if(!Product::where('user_id', '!=', 0)->where('slug', $data->slug)->where('price', $min)->first()){
                    $min = $v_prod->price;
                }
                if(!Product::where('user_id', '!=', 0)->where('slug', $data->slug)->where('price', $max)->first()){
                    $max = $v_prod->price;
                }
            }
            $base_prod->vendor_min_price = $min;
            $base_prod->vendor_max_price = $max;
            $base_prod->update();
        }
        //-- Logic Section Ends

        //--- Redirect Section
        $msg = __('Product Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function status($id1,$id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
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


            if($data->type == 'Digital')
                return view('vendor.product.edit.digital',compact('cats','data','sign'));
            elseif($data->type == 'License')
                return view('vendor.product.edit.license',compact('cats','data','sign'));
            else
                return view('vendor.product.edit.physical',compact('cats','data','selectedAttrs','catAttributes','childAttributes',
                'subAttributes','sign','brands','storesList','currentStores'));
        } else{
            return redirect()->route('vendor-prod-index');
        }

    }

    //*** GET Request
    public function copy($id)
    {
        $data = Product::findOrFail($id);
        $sign = Currency::where('id','=',1)->first();
        return view('vendor.product.copy',compact('data','sign'));
    }

    //*** POST Request
    public function copySubmit(Request $request, $id)
    {
        $user = Auth::user();
        $package = $user->subscribes()->orderBy('id','desc')->first();
        $prods = $user->products()->orderBy('id','desc')->get()->count();
        if($prods < $package->allowed_products || $package->allowed_products == 0)
        {
            $admin_prod = Product::where('id',$id);
            if(!$admin_prod->exists())
            {
                return redirect()->route('vendor.dashboard')->with('unsuccess',__('Sorry the page does not exist.'));
            } else{
                $admin_prod = $admin_prod->first();

                $existing_product = Product::where('user_id', Auth::user()->id)
                    ->where('brand_id', $admin_prod->brand_id)
                    ->where('category_id', $admin_prod->category_id)
                    ->where('mpn', $admin_prod->mpn)
                    ->first();
                if($existing_product){
                    return response()->json(array('errors' => [__("You already sell this product.")]));
                }
            }

            // Start Get info from Old Product
            $old = Product::findOrFail($id);
            if($old->category_id){
                $selectedAttrs = json_decode($old->attributes, true);
                $catAttributes = !empty($old->category->attributes) ? $old->category->attributes : '';
            }
            if($old->subcategory_id){
                $subAttributes = !empty($old->subcategory->attributes) ? $old->subcategory->attributes : '';
            }
            if($old->childcategory_id){
                $childAttributes = !empty($old->childcategory->attributes) ? $old->childcategory->attributes : '';
            }
            $sign = Currency::where('id','=',1)->first();
            $storesList = Generalsetting::all();
            $currentStores = $old->stores()->pluck('id')->toArray();

            // Replicate into a new product and change what's necessary
            $new = $old->replicateWithTranslations();
            //$new->slug = Str::slug($new->name,'-').'-'.strtolower(Str::random(3).$new->id.Str::random(3));
            $new->sku = Str::random(3).substr(time(), 6,8).Str::random(3);
            $new->ref_code = $new->sku;
            $new->product_type = "normal";

            $new->photo = $old->photo;
            $new->thumbnail = $old->thumbnail;
            $new->price = ($request->price) ? $request->price : $old->price;

            /* Vendor Exclusive Section */
            $new->user_id = Auth::user()->id;
            // Remove from all highlights
            $new->featured = false;
            $new->best = false;
            $new->top = false;
            $new->hot = false;
            $new->latest = false;
            $new->big = false;
            $new->trending = false;
            $new->sale = false;
            // Remove base product info
            $new->being_sold = false;
            $new->vendor_min_price = 0;
            $new->vendor_max_price = 0;
            $new->Push();

            // Associate with stores
            if($old->has('stores')) {
                $new->stores()->sync($old->stores);
            }
            $new->update();

            $old->being_sold = 1;

            // Find admin product
            $base_prod = Product::where('user_id', 0)->where('slug', $old->slug)->first();
            $max = $base_prod->vendor_max_price;
            $min = $base_prod->vendor_min_price;

            // For each vendor product, works with its price
            foreach(Product::where('user_id', '!=', 0)->where('slug', $old->slug)->get() as $v_prod){
                // Price higher than max price
                if($v_prod->price >= $max){
                    $max = $v_prod->price;
                }
                // Price lower than min price
                if($v_prod->price <= $min){
                    $min = $v_prod->price;
                }
                // Price between both but there's no such price at Database (just set current price as min/max)
                // Prevents an old price to be related with admin product if there's no Vendor product with that price anymore.
                if($v_prod->price >= $min && $v_prod->price <= $max){
                    if(!Product::where('user_id', '!=', 0)->where('slug', $old->slug)->where('price', $min)->first()){
                        $min = $v_prod->price;
                    }
                    if(!Product::where('user_id', '!=', 0)->where('slug', $old->slug)->where('price', $max)->first()){
                        $max = $v_prod->price;
                    }
                }
                $base_prod->vendor_min_price = $min;
                $base_prod->vendor_max_price = $max;
                $base_prod->update();
            }

            $old->update();

            $msg = __('Product Copied Successfully.');
            return response()->json($msg);
        } else return response()->json(array('errors' => [__("You cannot add more Products.")]));
    }

    //*** GET Request
    public function destroy($id)
    {

        $data = Product::findOrFail($id);
        if($data->galleries->count() > 0)
        {
            foreach ($data->galleries as $gal) {
                    if (file_exists(public_path().'/storage/images/galleries/'.$gal->photo)) {
                        unlink(public_path().'/storage/images/galleries/'.$gal->photo);
                    }
                $gal->delete();
            }

        }

        if($data->ratings->count() > 0)
        {
            foreach ($data->ratings  as $gal) {
                $gal->delete();
            }
        }
        if($data->wishlists->count() > 0)
        {
            foreach ($data->wishlists as $gal) {
                $gal->delete();
            }
        }
        if($data->clicks->count() > 0)
        {
            foreach ($data->clicks as $gal) {
                $gal->delete();
            }
        }
        if($data->comments->count() > 0)
        {
            foreach ($data->comments as $gal) {
            if($gal->replies->count() > 0)
            {
                foreach ($gal->replies as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }
        $data->delete();

        // Find admin product
        $base_prod = Product::where('user_id', 0)->where('slug', $data->slug)->first();
        $max = $base_prod->vendor_max_price;
        $min = $base_prod->vendor_min_price;

        // For each vendor product, works with its price
        foreach(Product::where('user_id', '!=', 0)->where('slug', $data->slug)->get() as $v_prod){
            // Price higher than max price
            if($v_prod->price >= $max){
                $max = $v_prod->price;
            }
            // Price lower than min price
            if($v_prod->price <= $min){
                $min = $v_prod->price;
            }
            // Price between both but there's no such price at Database (just set current price as min/max)
            // Prevents an old price to be related with admin product if there's no Vendor product with that price anymore.
            if($v_prod->price >= $min && $v_prod->price <= $max){
                if(!Product::where('user_id', '!=', 0)->where('slug', $data->slug)->where('price', $min)->first()){
                    $min = $v_prod->price;
                }
                if(!Product::where('user_id', '!=', 0)->where('slug', $data->slug)->where('price', $max)->first()){
                    $max = $v_prod->price;
                }
            }
            $base_prod->vendor_min_price = $min;
            $base_prod->vendor_max_price = $max;
            $base_prod->update();
        }

        $admin_prod = Product::isActive()
        ->select('products.*', DB::raw('count(*) as count'))
        ->where('slug', $data->slug)->groupBy('mpn')->groupBy('category_id')->groupBy('brand_id')->first();
        if($admin_prod->count <= 1){
            $admin_prod->being_sold = 0;
            $admin_prod->update();
        }

        //--- Redirect Section
        $msg = __('Product Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

// PRODUCT DELETE ENDS
    }
}
