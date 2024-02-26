<?php

namespace App\Http\Controllers\Admin;

use DB;
use DOMDocument;
use Carbon\Carbon;
use App\Models\Brand;
use League\Csv\Reader;
use App\Helpers\Helper;
use App\Models\Gallery;
use App\Models\License;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Attribute;
use League\Csv\Statement;
use App\Classes\XMLHelper;
use App\Enums\AssociationType;
use App\Models\Gallery360;
use App\Events\BackInStock;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Facades\MercadoLivre;
use App\Models\Childcategory;
use App\Models\Generalsetting;
use App\Models\AttributeOption;
use Yajra\DataTables\DataTables;
use App\Models\ProductTranslation;
use App\Models\CategoryTranslation;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\SubcategoryTranslation;
use Illuminate\Support\Facades\Session;
use App\Models\ChildcategoryTranslation;
use Illuminate\Support\Facades\DB as FacadeDB;

use function League\Csv\delimiter_detect;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    protected $store_id;
    protected $brand_id;
    protected $category_id;
    private $xml_helper;

    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->xml_helper = new XMLHelper();

        parent::__construct();
    }

    public function updateXMLComprasParaguai()
    {
        try {
            $this->xml_helper->updateComprasparaguai();

            $msg = __("Compras Paraguai XML successfully updated.");
            return response()->json($msg);
        } catch (\Exception $e) {
            Log::error('error_update_loja_compras_xml', [$e->getMessage()]);
        }
    }

    public function updateXMLGoogleAndFacebook()
    {
        try {
            $this->xml_helper->updateLojaGoogle();
            $this->xml_helper->updateLojaFacebook();

            $msg = __('Google and Facebook XMLs successfully updated.');
            return response()->json($msg);
        } catch (\Exception $e) {
            Log::error('error_update_loja_facebook_and_google_xml', [$e->getMessage()]);
        }
    }

    //*** JSON Request
    public function datatables($status = null)
    {
        $datas = Product::where('user_id', 0);
        if ($status == 'active') {
            $datas = $datas->where('status', '=', 1)->orderBy('id', 'desc');
        } elseif ($status == "inactive") {
            $datas = $datas->where('status', '=', 0)->orderBy('id', 'desc');
        } elseif ($status == 'without_image') {
            $datas = $datas->whereNull('photo')->orWhere('photo', '=', "")->orderBy('id', 'desc');
        } elseif ($status == 'without_details') {
            $datas = $datas->whereTranslation('details', null, $this->lang->locale)->orderBy('id', 'desc');
        } elseif ($status == 'featured') {
            $datas = $datas->where('featured', '=', 1)->orderBy('id', 'desc');
        } elseif ($status == 'latest') {
            $datas = $datas->where('latest', '=', 1)->orderBy('id', 'desc');
        } elseif ($status == 'without_category') {
            $datas = $datas->where('category_id', '=', null)->orWhere('category_id', '=', 0)->orderBy('id', 'desc');
        } elseif ($status == 'active_without_image') {
            $datas = $datas->whereRaw('(photo is null or photo = "")')->where('status', '=', 1)->orderBy('id', 'desc');
        } elseif ($status == 'with_image') {
            $datas = $datas->whereNotNull('photo')->where('photo', '<>', '')->orderBy('id', 'desc');
        } elseif ($status == 'Activate_products_with_image') {
            $datas = $datas->where('status', '=', 1)->whereNotNull('photo')->where('photo', '<>', '')->orderBy('id', 'desc');
        } elseif ($status == 'Inative_products_with_image') {
            $datas = $datas->where('status', '=', 0)->whereNotNull('photo')->where('photo', '<>', '')->orderBy('id', 'desc');
        } elseif ($status == 'Out-of-stock_and_active_products') {
            $datas = $datas->where('stock', '=', 0)->where('status', '=', 1)->orderBy('id', 'desc');
        } elseif ($status == 'Stock_and_inactive_products') {
            $datas = $datas->where('stock', '>=', 1)->where('status', '=', 0)->orderBy('id', 'desc');
        }
        elseif ($status == 'system_name') {
            $query1 = DB::table('products')
                ->select('products.id as id1')
                ->join('product_translations', function ($join) {
                    $join->on('products.id', '=', 'product_translations.product_id');
                })
                ->whereRaw('products.external_name = product_translations.name')
                ->where('product_translations.locale', '=', $this->lang->locale);
            $datas = $datas->whereIn('id', $query1->pluck('id1'))->orderBy('id', 'desc');
        } elseif ($status == "catalog") {
            $datas = $datas->where('is_catalog', '=', 1)->orderBy('id', 'desc');
        } elseif ($status == "with_tags") {
            $query = DB::table('products')
                ->select('products.id as id1')
                ->join('product_translations', function ($join) {
                    $join->on('products.id', '=', 'product_translations.product_id');
                })
                ->where('product_translations.features', '!=', null)->where('product_translations.features', '!=', "")
                ->where('product_translations.locale', '=', $this->lang->locale);
            $datas = $datas->whereIn('id', $query->pluck('id1'))->orderBy('id', 'desc');
        } elseif ($status == "without_tags") {
            $datas = $datas->whereTranslation('features', null, $this->lang->locale)->orderBy('id', 'desc');
        } else {
            $datas = $datas->orderBy('id', 'desc');
        }
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('brand', function (Product $data) {
                return $data->brand->name;
            })
            ->editColumn('category', function (Product $data) {
                return $data->category->name;
            })
            ->editColumn('store', function (Product $data) {
                foreach ($data->stores as $store) {
                    return $store->domain;
                }
            })
            ->addColumn('action', function (Product $data) {
                $meli = null;
                if (config('mercadolivre.is_active')) {
                    $meli = '<a href="' . route('admin-prod-meli-send', $data->id) . '" ><i class="fas fa-upload"></i> ' . __("Send to Mercado Livre") . '<a/>';
                    if ($data->mercadolivre_id) {
                        $meli = '<a href="' . route('admin-prod-meli-update', $data->id) . '" ><i class="fas fa-upload"></i> ' . __("Update at Mercado Livre") . '<a/>';
                    }
                }
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list"><a href="' . route('admin-prod-edit', $data->id) . '"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-prod-copy', $data->id) . '" data-toggle="modal" data-target="#confirm-copy" class="delete"><i class="fas fa-edit"></i> ' . __('Copy') . '</a>
                        ' . $meli . '
                        <a href="javascript" data-header="' . __("Image Gallery") . '" class="set-gallery-product" data-toggle="modal" data-target="#setgallery">
                        <input type="hidden" value="' . $data->id . '"><i class="fas fa-eye"></i> ' . __('View Gallery') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-prod-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                    </div>
                </div>';
            })
            ->filterColumn('brand_id', function ($query, $keyword) {
                $this->brand_id = $keyword;
                $query->where('brand_id', $this->brand_id);
            })
            ->filterColumn('category_id', function ($query, $keyword) {
                $this->category_id = $keyword;
                $query->where('category_id', $this->category_id);
            })
            ->filterColumn('store_id', function ($query, $keyword) {
                $this->store_id = $keyword;
                $query->whereHas('stores', function ($query) {
                    $query->where('store_id', $this->store_id);
                });
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->filterColumn('features', function ($query, $keyword) {
                $query->whereTranslationLike('features', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('name', function (Product $data) {
                $this->useStoreLocale();
                $name = mb_strlen(strip_tags($data->name), 'utf-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'utf-8') . '...' : strip_tags($data->name);
                if (config('mercadolivre.is_active') && $data->mercadolivre_id) {
                    $mercadolivre_id = substr($data->mercadolivre_id, 0, 3) . '-' . substr($data->mercadolivre_id, 3, 10);
                    $text = '<small> Anúncio Mercado Livre <a target="_blank" href="https://produto.mercadolivre.com.br/' . $mercadolivre_id . '">' . $mercadolivre_id . '</a> <i
                    class="fas fa-check"></i></small>';
                    $name .= $text;
                }
                $id = '<small>ID: <a href="' . route('front.product', $data->slug) . '?admin-view=true" target="_blank">' . sprintf("%'.08d", $data->id) . '</a></small>';
                $id2 = '';
                if (config('features.marketplace')) {
                    $id2 = $data->user_id != 0 ? (count($data->user->products) > 0 ? '<small class="ml-2"> ' . __('VENDOR') . ': <a href="' . route('admin-vendor-show', $data->user_id) . '" target="_blank">' . $data->user->shop_name . '</a></small>' : '') : '';
                }
                $id3 = $data->type == 'Physical' ? '<small class="ml-2"> SKU: <a href="' . route('front.product', $data->slug) . '?admin-view=true" target="_blank">' . $data->sku . '</a></small>' : '';
                $id4 = '<small class="ml-2"> ' . __('REF CODE') . ': ' . $data->ref_code . '</small>';
                $fast_edit_btn = '<a title="' . __("Edit") . '" data-href="' . route('admin-prod-fastedit', $data->id) . '" class="fasteditbtn" data-header="' . __("Edit") . " " . $data->ref_code . '" data-toggle="modal" data-target="#fast_edit_modal"><i class="fas fa-edit text-primary"></i></a>';
                
                $associatedProducts = $data->associatedProducts();

                if ($associatedProducts->count() > 0) {
                    $name .= ' *';
                }

                $this->useAdminLocale();

                return  $fast_edit_btn . $name . '<br>' . $id . $id3 . $id4 . $id2;
            })

            ->editColumn('features', function (Product $data) {
                return !empty($data->features[1]) ? $data->features[0] . ", " . $data->features[1] : $data->features;
            })

            ->editColumn('price', function (Product $data) {
                $sign = Currency::where('id', '=', 1)->first();
                $price = number_format($data->price * $sign->value, $sign->decimal_digits, $sign->decimal_separator, $sign->thousands_separator);
                $price = $sign->sign . $price;
                return  $price;
            })
            ->editColumn('photo', function (Product $data) {
                if (file_exists(public_path() . '/storage/images/thumbnails/' . $data->thumbnail)) {
                    return asset('storage/images/thumbnails/' . $data->thumbnail);
                }

                if ($this->storeSettings->ftp_folder) {
                    return htmlentities($data->thumbnail);
                }

                return asset('assets/images/noimage.png');
            })
            ->editColumn('stock', function (Product $data) {
                $stck = (string)$data->stock;
                if ($stck == "0") {
                    return __("Out Of Stock!");
                } elseif ($stck == null) {
                    return __("Unlimited");
                } else {
                    return $data->stock;
                }
            })
            ->addColumn('status', function (Product $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxStatus" id="checkbox-status-' . $data->id . '" name="' . route('admin-prod-status', ['id1' => $data->id, 'id2' => $data->status]) . '"' . $s . '><span class="slider round"></span></label></div>';
            })
            ->addColumn('featured', function (Product $data) {
                return '<a data-href="' . route('admin-prod-feature', $data->id) . '" class="feature add-btn" data-toggle="modal" data-target="#modal2" data-header="' . __('Highlight') . '"><i class="icofont-star" data-toggle="tooltip" title=' . __("Featured") . '></i> ' . __('Featured') . '</a>';
            })
            ->addColumn('bulk', function (Product $data) {
                return '<div class="custom-control custom-checkbox">
                        <input type="checkbox" name="bulk_' . $data->id . '" class="custom-control-input product-bulk-check"
                        id="bulk_' . $data->id . '" value="' . $data->id . '">
                        <label class="custom-control-label" for="bulk_' . $data->id . '"></label>
                        </div>';
            })
            ->rawColumns(['bulk', 'photo', 'action', 'name', 'price', 'stock', 'status', 'featured'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $filters = [
            "all" => __('All Products'),
            "active" => __('Active Products'),
            "inactive" => __('Inactive Products'),
            "without_image" => __('Without Image'),
            "active_without_image" => __('Active Without Image'),
            "with_tags" => __('With Tags'),
            "without_tags" => __('Without Tags'),
            "without_details" => __('Without Details'),
            "featured" => __('Featured'),
            "latest" => __('Latest'),
            "without_category" => __('Without Category'),
            "system_name" => __('With System Name'),
            "with_image" => __('With Image'),
            "Activate_products_with_image" => __('Active products with image'),
            "Inative_products_with_image" => __('Inative products with image'),
            "Out-of-stock_and_active_products" => __('Out-of-stock and active products'),
            "Stock_and_inactive_products" => __('Stock and inactive products')
        ];

        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::where('id', '=', 1)->first();
        $storesList = Generalsetting::all();

        return view('admin.product.index', compact('filters', 'cats', 'brands', 'sign', 'storesList'));
    }

    //*** GET Request
    public function types()
    {
        return view('admin.product.types');
    }

    //*** GET Request
    public function createPhysical()
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::where('id', '=', 1)->first();
        $storesList = Generalsetting::all();
        return view('admin.product.create.physical', compact('cats', 'sign', 'brands', 'storesList'));
    }

    //*** GET Request
    public function createDigital()
    {
        $cats = Category::all();
        $sign = Currency::where('id', '=', 1)->first();
        return view('admin.product.create.digital', compact('cats', 'sign'));
    }

    //*** GET Request
    public function createLicense()
    {
        $cats = Category::all();
        $sign = Currency::where('id', '=', 1)->first();
        return view('admin.product.create.license', compact('cats', 'sign'));
    }

    //*** GET Request
    public function status($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    //*** GET Featured
    public function featured($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->featured = $id2;
        $data->update();
    }

    //*** GET Request
    public function catalog($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->is_catalog = $id2;
        $data->update();
        if ($id2 == 1) {
            $msg = __("Product added to catalog successfully.");
        } else {
            $msg = __("Product removed from catalog successfully.");
        }

        return response()->json($msg);
    }

    //*** POST Request
    public function uploadUpdate(Request $request, $id)
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
        $image_name = time() . Str::random(8) . '.png';
        $path = 'storage/images/products/' . $image_name;
        file_put_contents($path, $image);
        if ($data->photo != null) {
            if (file_exists(public_path() . '/storage/images/products/' . $data->photo)) {
                unlink(public_path() . '/storage/images/products/' . $data->photo);
            }
        }
        $input['photo'] = $image_name;
        $data->update($input);
        if ($data->thumbnail != null) {
            if (file_exists(public_path() . '/storage/images/thumbnails/' . $data->thumbnail)) {
                unlink(public_path() . '/storage/images/thumbnails/' . $data->thumbnail);
            }
        }

        $img = Image::make(public_path() . '/storage/images/products/' . $data->photo)->resize(285, 285);
        $thumbnail = time() . Str::random(8) . '.png';
        $img->save(public_path() . '/storage/images/thumbnails/' . $thumbnail);
        $data->thumbnail  = $thumbnail;
        $data->update();
        return response()->json(['status' => true, 'file_name' => $image_name]);
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            // 'photo'      => 'required',
            'file'       => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
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
        $data = new Product;
        $sign = Currency::where('id', '=', 1)->first();
        $input = $this->withRequiredFields($request->all(), ['name']);
        $input['show_price'] = (bool) $request->show_price ?? false;

        // Check File
        if ($file = $request->file('file')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/files', $name);
            $input['file'] = $name;
        }

        if (!empty($input['photo'])) {
            $image = $request->photo;
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name = time() . Str::random(8) . '.png';
            $path = 'storage/images/products/' . $image_name;
            file_put_contents($path, $image);
            $input['photo'] = $image_name;
        }
        //-- Translations Section
        // Will check each field in language 1 and then for each other language

        // Check Seo
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        // Check Features
        if (empty($input[$this->lang->locale]['features']) || empty($request->colors)) {
            $input[$this->lang->locale]['features'] = null;
            $input['colors'] = null;
        } else {
            if (in_array(null, $input[$this->lang->locale]['features']) || in_array(null, $request->colors)) {
                $input[$this->lang->locale]['features'] = implode('', str_replace('', ' ', $input[$this->lang->locale]['features']));
                if (!empty($request->colors)) {
                    $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
                }
            } else {
                if (!empty($input[$this->lang->locale]['features'])) {
                    $input[$this->lang->locale]['features'] = implode(
                        ',',
                        str_replace(',', ' ', $input[$this->lang->locale]['features'])
                    );
                }
                if (!empty($request->colors)) {
                    $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
                }
            }
        }

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            if (!empty($input[$loc->locale]['meta_tag'])) {
                $input[$loc->locale]['meta_tag'] = implode(',', $input[$loc->locale]['meta_tag']);
            }

            if (!empty($input[$loc->locale]['features'])) {
                if (in_array(null, $input[$loc->locale]['features'])) {
                    $input[$loc->locale]['features'] = null;
                } else {
                    $input[$loc->locale]['features'] = implode(
                        ',',
                        str_replace(',', ' ', $input[$loc->locale]['features'])
                    );
                }
            }
        }
        //-- End Translations Section

        // Check Physical
        if ($request->type == "Physical") {
            if ($request->api) {
                $api_rules = [
                    'sku' => 'required|unique:products',
                    'price' => 'required',
                    'ref_code' => 'required'
                ];
                $validator = Validator::make($request->all(), $api_rules);
                if ($validator->fails()) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
                }
            }

            //--- Validation Section
            $rules = [
                'sku'      => 'min:1|unique:products',
                'ref_code'      => 'max:50|unique:products',
                'mpn'      => 'max:50'
            ];

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
                    foreach ($request->size as $key => $size) {
                        $size_without_comma[$key] = str_replace(',', '.', $size);
                    }
                    $input['size'] = implode(',', $size_without_comma);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $input['size_price'] = implode(',', $request->size_price);
                    $stck = 0;
                    foreach ($request->size_qty as $key => $size) {
                        $stck += (int)$request->size_qty[$key];
                    }
                    $input['stock'] = $stck;
                }
            }


            // Check Whole Sale
            if (empty($request->whole_check)) {
                $input['whole_sell_qty'] = null;
                $input['whole_sell_discount'] = null;
            } else {
                if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                    $input['whole_sell_qty'] = null;
                    $input['whole_sell_discount'] = null;
                } else {
                    $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                    $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                }
            }

            // Check Color
            if (empty($request->color_check)) {
                $input['color'] = null;
                $input['color_qty'] = null;
                $input['color_price'] = null;
            } else {
                if (in_array(null, $request->color) || in_array(null, $request->color_qty)) {
                    $input['color'] = null;
                    $input['color_qty'] = null;
                    $input['color_price'] = null;
                } else {
                    $input['color'] = implode(',', $request->color);
                    $input['color_qty'] = implode(',', $request->color_qty);
                    $input['color_price'] = implode(',', $request->color_price);
                    $stck = 0;
                    foreach ($request->color_qty as $key => $color) {
                        $stck += (int)$request->color_qty[$key];
                    }
                    $input['stock'] = $stck;

                    $input['color_gallery'] = null;

                    // Color Gallery
                    if ($files_arr = $request->file('color_gallery')) {
                        foreach ($files_arr as  $key => $file_arr) {
                            foreach ($file_arr as $key => $file) {
                                $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                $input['color_gallery'] .= $name . "|";
                                $file->move('storage/images/color_galleries', $name);
                            }
                            $input['color_gallery'] = substr_replace($input['color_gallery'], "", -1);
                            $input['color_gallery'] .= ",";
                        }
                        $input['color_gallery'] = substr_replace($input['color_gallery'], "", -1);
                    }
                }
            }

            if (empty($request->material_check)) {
                $input['material'] = null;
                $input['material_gallery'] = null;
                $input['material_qty'] = null;
                $input['material_price'] = null;
            } else {
                if (in_array(null, $request->material) || in_array(null, $request->material_qty)) {
                    $input['material'] = null;
                    $input['material_qty'] = null;
                    $input['material_price'] = null;
                    $input['material_gallery'] = null;
                } else {
                    $input['material'] = implode(",", $request->material);
                    $input['material_qty'] = implode(',', $request->material_qty);
                    $input['material_price'] = implode(',', $request->material_price);
                    $input['material_gallery'] = null;
                    $stck = 0;
                    foreach ($request->material_qty as $key => $material) {
                        $stck += (int)$request->material_qty[$key];
                    }
                    $input['stock'] = $stck;
                    if ($files_arr = $request->file('material_gallery')) {
                        foreach ($files_arr as $key => $file_arr) {
                            foreach ($file_arr as $key => $file) {
                                $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                $input['material_gallery'] .= $name . "|";
                                $file->move("storage/images/material_galleries", $name);
                            }
                            $input['material_gallery'] = substr_replace($input['material_gallery'], "", -1);
                            $input['material_gallery'] .= ",";
                        }
                        $input['material_gallery'] = substr_replace($input['material_gallery'], "", -1);
                    }
                }
            }


            // Check Measurement
            if ($request->measure_check == "") {
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
        $input['price'] = (floatval($input['price']) / $sign->value);
        $input['previous_price'] = (floatval($input['previous_price']) / $sign->value);

        // store filtering attributes for physical product
        $attrArr = [];
        if (!empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
            if (!empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("attr_" . "$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["attr_" . "$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["attr_" . "$in_name" . "_price"];
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
                    if ($request->has("attr_" . "$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["attr_" . "$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["attr_" . "$in_name" . "_price"];
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
                    if ($request->has("attr_" . "$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["attr_" . "$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["attr_" . "$in_name" . "_price"];
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
            $input['attributes'] = null;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }

        // Save Data
        $data->fill($input)->save();

        $prod = Product::find($data->id);
        $associated_colors = $request->input('associated_colors', []);
        $associated_sizes = $request->input('associated_sizes', []);
        $associated_looks = $request->input('associated_looks', []);
        $prod->associatedProducts()->detach();
        $prod->associatedProducts()->attach($associated_colors, ['association_type' => AssociationType::Color]);
        $prod->associatedProducts()->attach($associated_sizes, ['association_type' => AssociationType::Size]);
        $prod->associatedProducts()->attach($associated_looks, ['association_type' => AssociationType::Look]);

        if ($associated_colors) {
            foreach ($associated_colors as $color) {
                $colorProduct = Product::find($color);
                $productsAssociatedId[] = $colorProduct['id'];

                $existingInverseAssociation = $colorProduct->associatedProducts()->where('associated_product_id', $prod->id)->where('association_type', AssociationType::Color)->exists();
                if (!$existingInverseAssociation) {
                    $colorProduct->associatedProducts()->attach($prod->id, ['association_type' => AssociationType::Color]);
                }
            }

            $productWithAssociations = [];
            foreach ($productsAssociatedId as $productColorPk) {
                $product_pk = new Product($productColorPk);
                foreach ($productsAssociatedId as $productColorFk) {
                    $product_fk = new Product($productColorFk);
                    if ($product_pk->id != $product_fk->id) {
                        $existingInverseAssociation =  $product_pk->associatedProducts()->where('associated_product_id',  $product_fk->id)->where('association_type', AssociationType::Color)->exists();

                        if (!$existingInverseAssociation) {
                            $productWithAssociations[] = [
                                'product_id' => $product_pk->id,
                                'associated_product_id' => $product_fk->id,
                                'association_type' => AssociationType::Color->value

                            ];
                        }
                    }
                }
            }

            if (!$productWithAssociations) {
                FacadeDB::table('associated_products')->insert($productWithAssociations);
            }
        }
    
        # Validate Redplay
        if ($request->redplay_login && $request->redplay_password && $request->redplay_code) {
            $redplayData = Product::sanitizeRedplayData([
                'redplay_login' => $request->redplay_login,
                'redplay_password' => $request->redplay_password,
                'redplay_code' => $request->redplay_code
            ]);

            # Não permite que itens completamente vazios sejam inseridos no banco.
            foreach ($redplayData as $key => $redplay) {
                if (!$redplay['login'] && !$redplay['password'] && !$redplay['code']) {
                    unset($redplayData[$key]);
                }
            }

            # Cria ou atualiza novas licenças.
            foreach ($redplayData as $redplay) {
                $license = License::where('code', $redplay['code'])->first();

                if (!$license) {
                    $license = new License;
                }

                $license->product_id = $data->id;
                $license->login = $redplay['login'];
                $license->password = $redplay['password'];
                $license->code = $redplay['code'];
                $license->save();
            }
        }

        if ($prod->type != 'Physical') {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower(Str::random(3) . $data->id . Str::random(3));
        } else {
            $prod->slug = Str::slug($data->name, '-') . '-' . strtolower(Str::slug($data->sku));
        }
        $prod->update();

        if (!empty($input['photo'])) {
            // Set Thumbnail
            $img = Image::make(public_path() . '/storage/images/products/' . $input['photo'])->resize(285, 285);
            $thumbnail = time() . Str::random(8) . '.jpg';
            $img->save(public_path() . '/storage/images/thumbnails/' . $thumbnail);
            $prod->thumbnail  = $thumbnail;
            $prod->update();
        }

        $associatedProducts = $prod->associatedProducts()
        ->where('association_type', AssociationType::Size)
        ->get();

        foreach ($associatedProducts as $associatedProduct) {
            $associatedProduct->photo = $prod->photo;
            $associatedProduct->thumbnail = $prod->thumbnail;
            $associatedProduct->update();
        }

        // Add To Gallery If any    
        $lastid = $data->id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                if (in_array($key, $request->galval)) {
                    $gallery = new Gallery;
                    $name = time() . $file->getClientOriginalName();
                    $file->move('storage/images/galleries', $name);
                    $gallery['photo'] = $name;
                    $gallery['product_id'] = $lastid;
                    $gallery->save();
                }
            }
        }
        // Add To Gallery 360 If any
        $lastid = $data->id;
        if ($files = $request->file('gallery360')) {
            foreach ($files as  $key => $file) {
                if (in_array($key, $request->galval)) {
                    $gallery360 = new Gallery360;
                    $name = time() . $file->getClientOriginalName();
                    $file->move('storage/images/galleries360', $name);
                    $gallery360['photo'] = $name;
                    $gallery360['product_id'] = $lastid;
                    $gallery360->save();
                }
            }
        }

        //associates with stores
        if ($request->has('stores')) {
            $prod->stores()->sync($input['stores']);
        }

        # Validate Redplay License
        if ($request->redplay_license) {
            $licenseModel = new License;
            $licenseModel->product_id = $prod->id;
            $licenseModel->data = $request->redplay_license;

            $licenseModel->save();
        }

        //logic Section Ends

        //--- Redirect Section
        if ($request->has('bulk_form')) {
            return response()->json([
                'bulk_store' => true
            ]);
            exit;
        }

        if ($request->api) {
            return response()->json(array('status' => 'ok'), Response::HTTP_CREATED);
        }

        session()->flash('success', __('New Product Added Successfully.'));

        return response()->json(['redirect' => route('admin-prod-index')]);
        //--- Redirect Section Ends
    }

    //*** POST Request
    public function import()
    {
        $cats = Category::all();
        $sign = Currency::where('id', '=', 1)->first();
        return view('admin.product.productcsv', compact('cats', 'sign'));
    }

    public function prepareImport(Request $request)
    {
        $rules = ['csvfile' => 'required|mimes:csv,txt'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $filename = '';
        if ($file = $request->file('csvfile')) {
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move('storage/temp_files', $filename);
        }

        $row = -1; // desconsidere o header
        if (($fp = fopen(public_path('storage/temp_files/' . $filename), "r")) !== false) {
            while (($record = fgetcsv($fp)) !== false) {
                $row++;
            }
        }

        return response()->json(array(
            'rows' => $row,
            'fileName' => $filename,
            'message' => __("Preparing to insert new data, line:")
        ));
    }

    public function importSubmit(Request $request)
    {
        $filename = $request->fileName;
        $offset = $request->offset;
        $line = $offset + 2;
        $updateCheck = $request->updateCheck;

        $csv = Reader::createFromPath(public_path('storage/temp_files/' . $filename), 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

        $stmt = (new Statement())
            ->offset($offset)
            ->limit(1);
        $records = $stmt->process($csv);

        foreach ($records as $record) {
            if (!is_array($record) || count($record) < 2) {
                return response()->json(['error' => __('Insert a valid File')], 400);
            }

            $headers = $records->getHeader();
            foreach ($headers as $header) {
                if (strpos($header, '*') !== false && empty($record[$header])) {
                    return response()->json(array(
                        'errors' => __("The field: ") . $header . __(' in line: ') . $line . __(' cannot be empty')
                    ));
                }
            }
            $record = $this->validateRecord($record);
            $product = Product::where('sku', $record['sku'])->first();

            if (!$product || $product && $updateCheck) {
                $record['type'] = 'Physical';
                $record['previous_price'] = 0;
                $record['stock'] = ($record['stock'] === '' ? null : $record['stock']);

                $cat = CategoryTranslation::where(DB::raw('lower(name)'), strtolower($record['category_id']));
                if ($cat->exists()) {
                    $record['category_id'] = $cat->first()->category_id;
                    if ($record['subcategory_id'] != "") {
                        $scat = SubcategoryTranslation::where(DB::raw('lower(name)'), strtolower($record['subcategory_id']));
                        $record['subcategory_id'] = ($scat->exists() ? $scat->first()->subcategory_id : null);
                    } else {
                        $record['subcategory_id'] = null;
                    }
                    if ($record['childcategory_id'] != "") {
                        $chcat = ChildcategoryTranslation::where(DB::raw('lower(name)'), strtolower($record['childcategory_id']));
                        $record['childcategory_id'] = $chcat->exists() ? $chcat->first()->childcategory_id : null;
                    } else {
                        $record['childcategory_id'] = null;
                    }
                    if ($record['brand_id'] != "") {
                        $brand = Brand::where(DB::raw('lower(name)'), strtolower($record['brand_id']));
                        $record['brand_id'] = $brand->exists() ? $brand->first()->id : null;
                    } else {
                        $record['brand_id'] = null;
                    }

                    $record[$this->lang->locale]['name'] = $record['name'];
                    $record[$this->lang->locale]['details'] = $record['details'];
                    $record[$this->lang->locale]['features'] = array(0 => null);
                    $record['price'] = $record['price'] ? $record['price'] : 0;
                    $record['stores'] = array(1);
                    $record['bulk_form'] = true;
                    $request = new Request();
                    $request->replace($record);
                    if ($product && $updateCheck) {
                        return $this->update($request, $product->id);
                    }
                    return $this->store($request);
                } else {
                    return response()->json(array(
                        'errors' => __("No Category Found!") . __('in line') . ": " . $line
                    ));
                }
            } else {
                return response()->json(array(
                    'errors' => __("Duplicate Product Code! in line: ") . $line
                ));
            }
        }
    }

    public function endImport(Request $request)
    {
        $filename = $request->fileName;

        unlink(public_path('storage/temp_files/' . $filename));
        $msg = '<p>' . __('Bulk Product File Imported Successfully.') . '<a href="' . route('admin-prod-index') . '">' . __('View Product Lists.') . '</a></p>';
        $msg .= '<p>' . __('Total insert data: ') . '<span class="insertCount"></span></p>';
        $msg .= '<p>' . __('Total update products: ') . '<span class="updateCount"></span></p>';
        $msg .= '<p>' . __('Total erros data: ') . '<span class="errorCount"></span></p>';
        return response()->json($msg);
    }

    private function validateRecord($record)
    {
        $newKey = array(
            "sku*" => "sku",
            "category*" => "category_id",
            "subcategory" => "subcategory_id",
            "childcategory" => "childcategory_id",
            "brand" => "brand_id",
            "product name*" => "name",
        );
        $record = $this->renameRecord($record, $newKey);
        return $record;
    }

    private function renameRecord($record, $newKey)
    {
        foreach ($newKey as $key => $value) {
            $record[$value] = $record[$key];
            unset($record[$key]);
        }
        return $record;
    }

    private function cleanEmptyValues($record)
    {
        foreach ($record as $key => $value) {
            if (empty($value)) {
                unset($record[$key]);
            }
        }
        return $record;
    }

    //*** GET Request
    public function edit($id)
    {
        if (!Product::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $data = Product::findOrFail($id);

        $selectedAttrs = json_decode($data->attributes, true);
        $catAttributes = !empty($data->category->attributes) ? $data->category->attributes : '';
        $subAttributes = !empty($data->subcategory->attributes) ? $data->subcategory->attributes : '';
        $childAttributes = !empty($data->childcategory->attributes) ? $data->childcategory->attributes : '';
        $sign = Currency::where('id', '=', 1)->first();
        $storesList = Generalsetting::all();
        $currentStores = $data->stores()->pluck('id')->toArray();
        $associatedColors = $data->associatedProductsByColor->pluck('id')->toArray();
        $associatedSizes = $data->associatedProductsBySize->pluck('id')->toArray();
        $associatedLooks = $data->associatedProductsByLook->pluck('id')->toArray();
        $ftp_path = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $data->ref_code_int . '/');
        $ftp_gallery = [];
        if (File::exists($ftp_path)) {
            $files = scandir($ftp_path);
            $extensions = array('.jpg', '.jpeg', '.gif', '.png');
            foreach ($files as $file) {
                $file_extension = strtolower(strrchr($file, '.'));
                if (in_array($file_extension, $extensions) === true) {
                    $ftp_gallery[] = asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $data->ref_code_int . '/' . $file);
                }
            }
        }

        if ($data->type == 'Digital') {
            return view('admin.product.edit.digital', compact('cats', 'data', 'sign'));
        } elseif ($data->type == 'License') {
            return view('admin.product.edit.license', compact('cats', 'data', 'sign'));
        } else {
            return view('admin.product.edit.physical', compact(
                'cats',
                'data',
                'selectedAttrs',
                'catAttributes',
                'childAttributes',
                'subAttributes',
                'sign',
                'brands',
                'storesList',
                'currentStores',
                'ftp_gallery',
                'associatedColors',
                'associatedSizes',
                'associatedLooks'
            ));
        }
    }

    public function editMeli($id)
    {
        if (!Product::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        $data = Product::findOrFail($id);

        /* -----------------------
        *   NOME, CATEGORIA & ATRIBUTOS DE CATEGORIA
        * -----------------------*/

        $extraData = [];
        $warranties = [];
        $withoutWarrantyId = null;
        if ($data->mercadolivre_name) {
            $meli_category_id = MercadoLivre::getCategoryId($data->mercadolivre_name);

            $meli_category_attributes = MercadoLivre::getCategoryAttributes($meli_category_id);

            $extraData['meli_category_attributes'] = json_decode($meli_category_attributes);
            /*if($data->mercadolivre_category_attributes) {
                $extraData['meli_category_attributes'] = (object) array_merge((array) json_decode($meli_category_attributes), (array) json_decode($data->mercadolivre_category_attributes));
            }*/

            $selectedCategoryAttributes = json_decode($data->mercadolivre_category_attributes);

            # procura por atributos de categoria já preenchidos
            foreach ($extraData['meli_category_attributes'] as $key => $categoryAttribute) {
                if (isset($selectedCategoryAttributes->$key->value)) {
                    $categoryAttribute->value = $selectedCategoryAttributes->$key->value;
                }

                if (isset($selectedCategoryAttributes->$key->allowed_unit_selected)) {
                    foreach ($categoryAttribute->allowed_units as $allowedUnit) {
                        $allowedUnit->selected = false;
                        if ($allowedUnit->name === $selectedCategoryAttributes->$key->allowed_unit_selected) {
                            $allowedUnit->selected = true;
                        }
                    }
                    $categoryAttribute->selected_unit = $selectedCategoryAttributes->$key->allowed_unit_selected;
                }
            }

            /* -----------------------
            *   GARANTIA
            * -----------------------*/

            $warranties = MercadoLivre::getWarranties($meli_category_id);
            $withoutWarrantyId = null;

            # Encontra qual garantia é a gratuita para validar ID dinamicamente no Front
            foreach ($warranties as $warranty) {
                # Verifica se é Tipo de Garantia
                if (isset($warranty['values']) && $warranty['id'] === "WARRANTY_TYPE") {
                    foreach ($warranty['values'] as $warrantyType) {
                        if ($warrantyType->name === "Sem garantia") {
                            $withoutWarrantyId = $warrantyType->id;
                        }
                    }
                }
            }
        }

        /* -----------------------
        *   TIPO DE ANÚNCIO
        * -----------------------*/

        $listingTypes = MercadoLivre::getListingTypes();

        $listingTypesWithDetails = [];

        foreach ($listingTypes as $listingType) {
            $listingTypesWithDetails[$listingType->id]['site_id'] = $listingType->site_id;
            $listingTypesWithDetails[$listingType->id]['id'] = $listingType->id;
            $listingTypesWithDetails[$listingType->id]['name'] = $listingType->name;

            $detail = MercadoLivre::getListingTypeDetail($listingType->id);
            $listingTypesWithDetails[$listingType->id]['details'] = $detail;
            switch ($detail['configuration']->listing_exposure) {
                case 'lowest':
                    $detail['configuration']->listing_exposure = 'Baixíssima';
                    break;
                case 'low':
                    $detail['configuration']->listing_exposure = 'Baixa';
                    break;
                case 'mid':
                    $detail['configuration']->listing_exposure = 'Regular';
                    break;
                case 'high':
                    $detail['configuration']->listing_exposure = 'Alta';
                    break;
                case 'highest':
                    $detail['configuration']->listing_exposure = 'Altíssima';
                    break;
            }
        }

        return view('admin.product.edit.meli', compact('data', 'extraData', 'listingTypesWithDetails', 'warranties', 'withoutWarrantyId'));
    }

    //*** GET Request
    public function copy($id)
    {
        if (!Product::where('id', $id)->exists()) {
            return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        }

        // Start Get info from Old Product
        $old = Product::findOrFail($id);
        if ($old->category_id) {
            $selectedAttrs = json_decode($old->attributes, true);
            $catAttributes = !empty($old->category->attributes) ? $old->category->attributes : '';
        }
        if ($old->subcategory_id) {
            $subAttributes = !empty($old->subcategory->attributes) ? $old->subcategory->attributes : '';
        }
        if ($old->childcategory_id) {
            $childAttributes = !empty($old->childcategory->attributes) ? $old->childcategory->attributes : '';
        }

        $sign = Currency::where('id', '=', 1)->first();
        $storesList = Generalsetting::all();
        $currentStores = $old->stores()->pluck('id')->toArray();

        // Replicate into a new product and change what's necessary
        $new = $old->replicateWithTranslations();
        $new->slug = Str::slug($new->name, '-') . '-' . strtolower(Str::random(3) . $new->id . Str::random(3));
        $new->sku = Str::random(3) . substr(time(), 6, 8) . Str::random(3);
        $new->ref_code = $new->sku;
        $new->photo = null;
        $new->thumbnail = null;
        $new->Push();

        // Associate with stores
        if ($old->has('stores')) {
            $new->stores()->sync($old->stores);
        }
        $new->update();

        $msg = __('Product Copied Successfully.');
        return response()->json($msg);
    }
    //*** POST Request

    public function update(Request $request, $id)
    {
        // return $request;
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            'file'       => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            if ($request->api) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
            }
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //-- Logic Section
        $data = Product::findOrFail($id);
        $associated_colors = $request->input('associated_colors', []);
        $associated_sizes = $request->input('associated_sizes', []);
        $associated_looks = $request->input('associated_looks', []);

        $data->associatedProducts()->detach();
        $data->associatedProducts()->attach($associated_colors, ['association_type' => AssociationType::Color]);
        $data->associatedProducts()->attach($associated_sizes, ['association_type' => AssociationType::Size]);
        $data->associatedProducts()->attach($associated_looks, ['association_type' => AssociationType::Look]);

        if ($associated_colors) {
            foreach ($associated_colors as $color) {
                $colorProduct = Product::find($color);
                $productsAssociatedId[] = $colorProduct['id'];

                $existingInverseAssociation = $colorProduct->associatedProducts()->where('associated_product_id', $data->id)->where('association_type', AssociationType::Color)->exists();

                if (!$existingInverseAssociation) {
                    $colorProduct->associatedProducts()->syncWithoutDetaching([$data->id => ['association_type' => AssociationType::Color]]);
                }

                $data->associatedProducts()->syncWithoutDetaching([$colorProduct->id => ['association_type' => AssociationType::Color]]);
            }

            $productWithAssociations = [];
            foreach ($productsAssociatedId as $productColorPk) {
                $product_pk = new Product($productColorPk);
                foreach ($productsAssociatedId as $productColorFk) {
                    $product_fk = new Product($productColorFk);
                    if ($product_pk->id != $product_fk->id) {
                        $existingInverseAssociation =  $product_pk->associatedProducts()->where('associated_product_id',  $product_fk->id)->where('association_type', AssociationType::Color)->exists();

                        if (!$existingInverseAssociation) {
                            $productWithAssociations[] = [
                                'product_id' => $product_pk->id,
                                'associated_product_id' => $product_fk->id,
                                'association_type' => AssociationType::Color->value

                            ];
                        }
                    }
                }
            }


            if (!$productWithAssociations) {
                FacadeDB::table('associated_products')->insert($productWithAssociations);
            }
        }
        
        $associatedProducts = $data->associatedProducts()
            ->where('association_type', AssociationType::Size)
            ->get();

        foreach ($associatedProducts as $associatedProduct) {
            if ($data->photo != null) {
                $associatedProduct->photo = $data->photo;
                $associatedProduct->thumbnail = $data->thumbnail;
            }
            $associatedProduct->update();
        }
        
        $data->product_size = $request->input('product_size');
        
        if ($this->storeSettings->is_back_in_stock && $data->stock == 0 && $request->stock > 0) {
            BackInStock::dispatch($data, $this->storeSettings);
        }

        $sign = Currency::where('id', '=', 1)->first();
        //$input = $this->removeEmptyTranslations($request->all(), $data);
        $input = $this->withRequiredFields($request->except(['photo', 'thumbnail']), ['name']);
        $input['show_price'] = (bool) $request->show_price ?? false;

        //-- Translations Section
        // Will check each field in language 1 and then for each other language

        // Check Seo
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        if (!$request->api) {
            // Check Features
            if (empty($input[$this->lang->locale]['features']) || empty($request->colors)) {
                $input[$this->lang->locale]['features'] = null;
                $input['colors'] = null;
            } else {
                if (!in_array(null, $input[$this->lang->locale]['features']) && !in_array(null, $request->colors)) {
                    $input[$this->lang->locale]['features'] = implode(
                        ',',
                        str_replace(',', ' ', $input[$this->lang->locale]['features'])
                    );
                    $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
                } else {
                    if (in_array(null, $input[$this->lang->locale]['features']) || in_array(null, $request->colors)) {
                        $input[$this->lang->locale]['features'] = implode('', str_replace('', ' ', $input[$this->lang->locale]['features']));
                        if (!empty($request->colors)) {
                            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
                        }
                    } else {
                        $features = explode(',', $data->features);
                        $colors = explode(',', $data->colors);
                        $input[$this->lang->locale]['features'] = implode(',', $features);
                        $input['colors'] = implode(',', $colors);
                    }
                }
            }
        }

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            if (!empty($input[$loc->locale]['meta_tag'])) {
                $input[$loc->locale]['meta_tag'] = implode(',', $input[$loc->locale]['meta_tag']);
            }

            if (!empty($input[$loc->locale]['features'])) {
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
        }
        //-- End of Translations Section

        //Check Types
        if ($request->type_check == 1) {
            $input['link'] = null;
        } else {
            if ($data->file != null) {
                if (file_exists(public_path() . '/storage/files/' . $data->file)) {
                    unlink(public_path() . '/storage/files/' . $data->file);
                }
            }
            $input['file'] = null;
        }


        // Check Physical
        if ($data->type == "Physical") {
            //--- Validation Section
            $rules = [
                'sku' => 'min:1|unique:products,sku,' . $id,
                'ref_code' => 'max:50|unique:products,ref_code,' . $id,
                'mpn'      => 'max:50'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                if ($request->api) {
                    return response()->json(array('errors' => $validator->getMessageBag()->toArray()), Response::HTTP_BAD_REQUEST);
                }
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends

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
                    foreach ($request->size as $key => $size) {
                        $size_without_comma[$key] = str_replace(',', '.', $size);
                    }
                    $input['size'] = implode(',', $size_without_comma);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $input['size_price'] = implode(',', $request->size_price);
                    $stck = 0;
                    foreach ($request->size_qty as $key => $size) {
                        $stck += (int)$request->size_qty[$key];
                    }
                    $input['stock'] = $stck;
                }
            }

            if (!$request->api) {
                //Check Shipping
                if ($request->free_shipping == "") {
                    $input['free_shipping'] = null;
                }

                // Check Condition
                if ($request->product_condition_check == "") {
                    $input['product_condition'] = 0;
                }

                // Check Shipping Time
                if ($request->shipping_time_check == "") {
                    $input['ship'] = null;
                }

                // Check Whole Sale
                if (empty($request->whole_check)) {
                    $input['whole_sell_qty'] = null;
                    $input['whole_sell_discount'] = null;
                } else {
                    if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                        $input['whole_sell_qty'] = null;
                        $input['whole_sell_discount'] = null;
                    } else {
                        $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                        $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                    }
                }

                // Check Color
                if (empty($request->color_check)) {
                    $input['color'] = null;
                    $input['color_qty'] = null;
                    $input['color_price'] = null;
                    $input['color_gallery'] = null;
                } else {
                    if (in_array(null, $request->color) || in_array(null, $request->color_qty)) {
                        $input['color'] = null;
                        $input['color_qty'] = null;
                        $input['color_price'] = null;
                        $input['color_gallery'] = null;
                    } else {
                        $input['color'] = implode(',', $request->color);
                        $input['color_qty'] = implode(',', $request->color_qty);
                        $input['color_price'] = implode(',', $request->color_price);
                        $stck = 0;
                        foreach ($request->color_qty as $key => $color) {
                            $stck += (int)$request->color_qty[$key];
                        }
                        $input['stock'] = $stck;

                        $input['color_gallery'] = null;

                        // Color Gallery
                        if ($files_arr = $request->file('color_gallery')) {
                            /* Searches for "current gallery" by new photos key with the aim of substitution */
                            if ($request->color_gallery_current) {
                                foreach ($request->color_gallery_current as $current_key => $current_arr) {
                                    if (array_key_exists($current_key, $request->color_gallery)) {
                                        $input['color_gallery_current'][$current_key] = null;
                                        foreach ($request->color_gallery[$current_key] as $file) {
                                            $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                            $input['color_gallery_current'][$current_key] .= $name . "|";
                                            $file->move('storage/images/color_galleries', $name);
                                        }
                                        $input['color_gallery_current'][$current_key] = substr_replace($input['color_gallery_current'][$current_key], "", -1);
                                    } elseif (isset($request->color_gallery[$key])) {
                                        $input['color_gallery_current'][$key] = null;
                                        foreach ($request->color_gallery[$key] as $file) {
                                            $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                            $input['color_gallery_current'][$key] .= $name . "|";
                                            $file->move('storage/images/color_galleries', $name);
                                        }
                                        $input['color_gallery_current'][$key] = substr_replace($input['color_gallery_current'][$key], "", -1);
                                        break;
                                    }
                                }
                                $input['color_gallery'] = implode(",", $input['color_gallery_current']);
                            } else {
                                foreach ($files_arr as  $key => $file_arr) {
                                    foreach ($file_arr as $file_key => $file) {
                                        $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                        $input['color_gallery'] .= $name . "|";
                                        $file->move('storage/images/color_galleries', $name);
                                    }
                                    $input['color_gallery'] = substr_replace($input['color_gallery'], "", -1);
                                    $input['color_gallery'] .= ",";
                                }
                                $input['color_gallery'] = substr_replace($input['color_gallery'], "", -1);
                            }
                        } else {
                            if ($request->color_gallery_current) {
                                $input['color_gallery'] = implode(",", $request->color_gallery_current);
                            } else {
                                $input['color_gallery'] = null;
                            }
                        }
                    }
                }

                // Check Material
                if (empty($request->material_check)) {
                    $input['material'] = null;
                    $input['material_qty'] = null;
                    $input['material_price'] = null;
                    $input['material_gallery'] = null;
                } else {
                    if (in_array(null, $request->material) || in_array(null, $request->material_qty)) {
                        $input['material'] = null;
                        $input['material_gallery'] = null;
                        $input['material_qty'] = null;
                        $input['material_price'] = null;
                    } else {
                        $input['material'] = implode(",", $request->material);
                        $input['material_gallery'] = null;
                        $input['material_qty'] = implode(',', $request->material_qty);
                        $input['material_price'] = implode(',', $request->material_price);
                        $stck = 0;
                        foreach ($request->material_qty as $key => $material) {
                            $stck += (int)$request->material_qty[$key];
                        }
                        $input['stock'] = $stck;

                        if ($files_arr = $request->file('material_gallery')) {
                            if ($request->material_gallery_current) {
                                foreach ($request->material_gallery_current as $current_key => $current_arr) {
                                    if (array_key_exists($current_key, $request->material_gallery)) {
                                        $input['material_gallery_current'][$current_key] = null;
                                        foreach ($request->material_gallery[$current_key] as $file) {
                                            $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                            $input['material_gallery_current'][$current_key] .= $name . "|";
                                            $file->move('storage/images/material_galleries', $name);
                                        }
                                        $input['material_gallery_current'][$current_key] = substr_replace($input['material_gallery_current'][$current_key], "", -1);
                                    } elseif (isset($request->material_gallery[$key])) {
                                        $input['material_gallery_current'][$key] = null;
                                        foreach ($request->material_gallery[$key] as $file) {
                                            $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                            $input['material_gallery_current'][$key] .= $name . "|";
                                            $file->move('storage/images/material_galleries', $name);
                                        }
                                        $input['material_gallery_current'][$key] = substr_replace($input['material_gallery_current'][$key], "", -1);
                                        break;
                                    }
                                }
                                $input['material_gallery'] = implode(",", $input['material_gallery_current']);
                            } else {
                                foreach ($files_arr as  $key => $file_arr) {
                                    foreach ($file_arr as $file_key => $file) {
                                        $name = time() . Str::random(8) . "." . $file->getClientOriginalExtension();
                                        $input['material_gallery'] .= $name . "|";
                                        $file->move('storage/images/material_galleries', $name);
                                    }
                                    $input['material_gallery'] = substr_replace($input['material_gallery'], "", -1);
                                    $input['material_gallery'] .= ",";
                                }
                                $input['material_gallery'] = substr_replace($input['material_gallery'], "", -1);
                            }
                        } else {
                            if ($request->material_gallery_current) {
                                $input['material_gallery'] = implode(",", $request->material_gallery_current);
                            } else {
                                $input['material_gallery'] = null;
                            }
                        }
                    }
                }

                // Check Measure
                if ($request->measure_check == "") {
                    $input['measure'] = null;
                }
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

        $input['price'] = (floatval($input['price']) / $sign->value);
        $input['previous_price'] = (floatval($input['previous_price']) / $sign->value);

        // store filtering attributes for physical product
        $attrArr = [];
        if (!empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
            if (!empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("attr_" . "$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["attr_" . "$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["attr_" . "$in_name" . "_price"];
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
                    if ($request->has("attr_" . "$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["attr_" . "$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["attr_" . "$in_name" . "_price"];
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
                    if ($request->has("attr_" . "$in_name")) {
                        $attrArr["$in_name"]["values"] = $request["attr_" . "$in_name"];
                        $attrArr["$in_name"]["prices"] = $request["attr_" . "$in_name" . "_price"];
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
            $input['attributes'] = null;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }
        $data->update($input);
        $data = Product::findOrFail($id);

        if ($this->storeSettings->ftp_folder) {
            $new_slug = $data->slug;
        } else{
            $new_slug = Str::slug($data->name, '-') . '-' . strtolower(Str::slug($data->sku));
        }

        if (config("features.marketplace")) {
            $vendor_products = Product::where('slug', $data->slug)->where('user_id', '!=', 0)->get();
            foreach ($vendor_products as $v_prod) {
                // Save unique attrs
                $sku = $v_prod->sku;
                $ref_code = $v_prod->ref_code;
                $price = $v_prod->price;

                // Update using Admin Input
                $v_prod->update($input);

                // Recover old attrs and update Vendor Products
                $v_prod->sku = $sku;
                $v_prod->ref_code = $ref_code;
                $v_prod->price = $price;
                $v_prod->slug = $new_slug;
                $v_prod->update();
            }
        }

        $data->slug = $new_slug;
        $data->update($input);

        //associates with stores
        $data->stores()->detach();
        if ($request->has('stores')) {
            $data->stores()->sync($input['stores']);
        }

        # Validate Redplay
        if ($request->redplay_login && $request->redplay_password && $request->redplay_code) {
            $redplayData = Product::sanitizeRedplayData([
                'redplay_login' => $request->redplay_login,
                'redplay_password' => $request->redplay_password,
                'redplay_code' => $request->redplay_code
            ]);

            # Não permite que itens completamente vazios sejam inseridos no banco.
            foreach ($redplayData as $key => $redplay) {
                if (!$redplay['login'] && !$redplay['password'] && !$redplay['code']) {
                    unset($redplayData[$key]);
                }
            }

            # Remove licenças que não estão mais preenchidas no formulário.
            $licensesThatMustBeRemoved = License::whereIn('product_id', [$data->id])->whereNotIn('code', $request->redplay_code)->get();
            foreach ($licensesThatMustBeRemoved as $licenseToBeRemoved) {
                $licenseToBeRemoved->delete();
            }

            # Cria ou atualiza novas licenças.
            foreach ($redplayData as $redplay) {
                $license = License::where('code', $redplay['code'])->first();

                if (!$license) {
                    $license = new License;
                }

                $license->product_id = $data->id;
                $license->login = $redplay['login'];
                $license->password = $redplay['password'];
                $license->code = $redplay['code'];
                $license->save();
            }
        }


        //-- Logic Section Ends

        //--- Redirect Section
        if ($request->has('bulk_form')) {
            return response()->json([
                'bulk_update' => true
            ]);
            exit;
        }

        if ($request->api) {
            return response()->json(array('status' => 'ok'));
        }

        session()->flash('success', __('Product Updated Successfully.'));

        return response()->json(['redirect' => route('admin-prod-index')]);
        //--- Redirect Section Ends
    }

    public function updateMeli(Request $request, $id)
    {
        //-- Logic Section
        $data = Product::findOrFail($id);

        $input['mercadolivre_name'] = $request->mercadolivre_name;
        $input['mercadolivre_description'] = $request->mercadolivre_description;
        $input['mercadolivre_listing_type_id'] = $request->listing_type_id;
        $input['mercadolivre_price'] = $request->mercadolivre_price;

        # GARANTIA
        $input['mercadolivre_warranty_type_id'] = $request->warranty_type_id;
        $input['mercadolivre_warranty_type_name'] = $request->warranty_type_name;
        $input['mercadolivre_warranty_time'] = $request->warranty_time;
        $input['mercadolivre_warranty_time_unit'] = $request->warranty_time_unit;

        $input['mercadolivre_without_warranty'] = false;

        if (!$request->warranty_time && !$request->warranty_time_unit) {
            $input['mercadolivre_without_warranty'] = true;
        }

        if ($request->mercadolivre_category_attributes) {
            foreach ($request->mercadolivre_category_attributes as $key => $attribute) {
                $this->mercadolivre_category_attributes[$key] = [
                    'name' => array_key_first($attribute),
                    'value' => $attribute[array_key_first($attribute)]['value']
                ];

                if (isset($attribute[array_key_first($attribute)]['allowed_unit_selected'])) {
                    $this->mercadolivre_category_attributes[$key]['allowed_unit_selected'] = $attribute[array_key_first($attribute)]['allowed_unit_selected'];
                }
            }

            if ($request->mercadolivre_category_attributes) {
                $input['mercadolivre_category_attributes'] = json_encode($this->mercadolivre_category_attributes);
            }
        }

        $data->update($input);

        # Checks if UPDATE ON MERCADO LIVRE checkbox exists to create or edit Announcement.
        if ($request->has('update_check')) {
            return redirect()->route($data->mercadolivre_id ? 'admin-prod-meli-update' : 'admin-prod-meli-send', $data->id);
        }

        return redirect()->route('admin-prod-edit-meli', $data->id)->with('success', __('Product Updated Successfully.'));
        //--- Redirect Section Ends
    }
    //*** GET Request
    public function load($id)
    {
        $brand = Brand::findOrFail($id);
        return view('load.brand', compact('brand'));
    }

    //*** GET Request
    public function feature($id)
    {
        $data = Product::findOrFail($id);
        return view('admin.product.highlight', compact('data'));
    }

    //*** POST Request
    public function featuresubmit(Request $request, $id)
    {
        //-- Logic Section
        $data = Product::findOrFail($id);
        $input = $request->all();
        if ($request->featured == "") {
            $input['featured'] = 0;
        }
        if ($request->hot == "") {
            $input['hot'] = 0;
        }
        if ($request->best == "") {
            $input['best'] = 0;
        }
        if ($request->top == "") {
            $input['top'] = 0;
        }
        if ($request->latest == "") {
            $input['latest'] = 0;
        }
        if ($request->big == "") {
            $input['big'] = 0;
        }
        if ($request->trending == "") {
            $input['trending'] = 0;
        }
        if ($request->sale == "") {
            $input['sale'] = 0;
        }
        if ($request->is_discount == "") {
            $input['is_discount'] = 0;
            $input['discount_date'] = null;
        }
        if ($request->show_in_navbar == "") {
            $input['show_in_navbar'] = 0;
        }

        $data->update($input);
        //-- Logic Section Ends

        //--- Redirect Section
        $msg = __('Highlight Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function fastedit($id)
    {
        $data = Product::findOrFail($id);
        $sign = Currency::where('id', '=', 1)->first();
        return view('admin.product.fastedit', compact('data', 'sign'));
    }

    //*** GET Request
    public function bulkedit()
    {
        $cats = Category::all();
        $brands = Brand::orderBy('slug')->get();
        $sign = Currency::where('id', '=', 1)->first();
        $storesList = Generalsetting::all();
        return view('admin.product.bulkedit', compact('cats', 'brands', 'storesList', 'sign'));
    }

    //*** POST Request
    public function fasteditsubmit(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => 'required',
            // 'photo'      => 'required',
            'file'       => 'mimes:zip'
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __('Product Name in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $input = $this->withRequiredFields($request->all(), ['name']);
        //-- Logic Section
        $data = Product::findOrFail($id);
        $new_slug = Str::slug($data->name, '-') . '-' . strtolower(Str::slug($data->sku));



        if (config("features.marketplace")) {
            $vendor_products = Product::where('slug', $data->slug)->where('user_id', '!=', 0)->get();
            foreach ($vendor_products as $v_prod) {
                // Save unique attrs
                $sku = $v_prod->sku;
                $ref_code = $v_prod->ref_code;
                $price = $v_prod->price;
                // Update using Admin Input
                $v_prod->update($input);

                // Recover old attrs and update Vendor Products
                $v_prod->sku = $sku;
                $v_prod->ref_code = $ref_code;
                $v_prod->price = $price;
                $v_prod->slug = $new_slug;
                $v_prod->update();
            }
        }

        $data->slug = $new_slug;
        $data->update($input);
        //-- Logic Section Ends

        //--- Redirect Section
        $msg = __('Product Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** POST Request
    public function bulkeditsubmit(Request $request)
    {
        if (!$request->array_id) {
            return response()->json(__("No products selected to update."));
        }

        $productIds = explode(',', $request->array_id);

        $input = array_filter($request->all());
        $input = $this->removeEmptyTranslations($input, null, true);

        foreach ($productIds as $productId) {
            $data = Product::find($productId);

            if ($request->change_price_type) {
                $input['price'] = $data->applyBulkEditChangePrice($request->change_price_type, $request->price);
            }

            $data->update($input);
        }
        // --- Redirect Section
        $msg = __('Products Updated Successfully.');
        return response()->json($msg);
    }
    //*** POST Request
    public function bulkdeletesubmit(Request $request)
    {
        $array_id = explode(',', $request['array_id']);
        foreach ($array_id as $prod_id) {
            $this->destroy($prod_id);
        }
        // --- Redirect Section
        $msg = __('Products Deleted Successfully.');
        return response()->json($msg);
    }

    //*** GET Request
    public function destroy($id)
    {
        $data = Product::findOrFail($id);

        if ($data->galleries->count() > 0) {
            foreach ($data->galleries as $gal) {
                if (file_exists(public_path() . '/storage/images/galleries/' . $gal->photo) && !empty($gal->photo)) {
                    unlink(public_path() . '/storage/images/galleries/' . $gal->photo);
                }
                $gal->delete();
            }
        }

        if ($data->galleries360->count() > 0) {
            foreach ($data->galleries360 as $gal) {
                if (file_exists(public_path() . '/storage/images/galleries360/' . $gal->photo) && !empty($gal->photo)) {
                    unlink(public_path() . '/storage/images/galleries360/' . $gal->photo);
                }
                $gal->delete();
            }
        }

        if ($data->reports->count() > 0) {
            foreach ($data->reports as $gal) {
                $gal->delete();
            }
        }

        if ($data->ratings->count() > 0) {
            foreach ($data->ratings  as $gal) {
                $gal->delete();
            }
        }
        if ($data->wishlists->count() > 0) {
            foreach ($data->wishlists as $gal) {
                $gal->delete();
            }
        }
        if ($data->clicks->count() > 0) {
            foreach ($data->clicks as $gal) {
                $gal->delete();
            }
        }
        if ($data->comments->count() > 0) {
            foreach ($data->comments as $gal) {
                if ($gal->replies->count() > 0) {
                    foreach ($gal->replies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if ($data->photo != null) {
            if (!filter_var($data->photo, FILTER_VALIDATE_URL)) {
                if (file_exists(public_path() . '/storage/images/products/' . $data->photo)) {
                    unlink(public_path() . '/storage/images/products/' . $data->photo);
                }
            }
        }

        if (file_exists(public_path() . '/storage/images/thumbnails/' . $data->thumbnail) && $data->thumbnail != "") {
            unlink(public_path() . '/storage/images/thumbnails/' . $data->thumbnail);
        }

        if ($data->file != null) {
            if (file_exists(public_path() . '/storage/files/' . $data->file)) {
                unlink(public_path() . '/storage/files/' . $data->file);
            }
        }

        //remove from any store
        $data->stores()->detach();

        $data->delete();
        //--- Redirect Section
        $msg = __('Product Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

        // PRODUCT DELETE ENDS
    }

    public function getAttributes(Request $request)
    {
        $model = '';
        if ($request->type == 'category') {
            $model = 'App\Models\Category';
        } elseif ($request->type == 'subcategory') {
            $model = 'App\Models\Subcategory';
        } elseif ($request->type == 'childcategory') {
            $model = 'App\Models\Childcategory';
        }

        $attributes = Attribute::where('attributable_id', $request->id)->where('attributable_type', $model)->get();
        $attrOptions = [];
        foreach ($attributes as $key => $attribute) {
            $attribute->name = $attribute->name;
            $options = AttributeOption::where('attribute_id', $attribute->id)->get();
            foreach ($options as $opt) {
                $opt->name = $opt->name;
            }
            $attrOptions[] = ['attribute' => $attribute, 'options' => $options];
        }
        return response()->json($attrOptions);
    }

    public function deleteProductImage(Request $request)
    {
        $data = Product::findOrFail($request->id);
        if ($data->photo != null) {
            if (!filter_var($data->photo, FILTER_VALIDATE_URL)) {
                if (file_exists(public_path() . '/storage/images/products/' . $data->photo)) {
                    unlink(public_path() . '/storage/images/products/' . $data->photo);
                }
            }
        }
        if ($data->thumbnail != null) {
            if (file_exists(public_path() . '/storage/images/thumbnails/' . $data->thumbnail) && $data->thumbnail != "") {
                unlink(public_path() . '/storage/images/thumbnails/' . $data->thumbnail);
            }
        }
        //$data->update(['photo' => null, 'thumbnail' => null]);
        $msg = __('Image Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => $msg
        ]);
    }

    public function generateThumbnailsFtp()
    {
        if (resolve('storeSettings')->ftp_folder) {
            $prods = Product::byStore()->where('status', '=', 1)->get();
            foreach ($prods as $prod) {
                Helper::generateProductThumbnailsFtp(resolve('storeSettings')->ftp_folder, $prod->ref_code_int);
            }
            $msg = __('Thumbnails successfully updated!');
            return response()->json([
                'status' => true,
                'message' => $msg
            ]);
        } else {
            $msg = __("You can't update thumbnails since FTP Integration is disabled.");
            return response()->json([
                'status' => false,
                'message' => $msg
            ]);
        }
    }

    public function generateThumbnails()
    {
        $updated = 0;
        $products = Product::whereRaw('status = 1 and photo is not null')->get();
        foreach ($products as $product) {
            $thumb_path = public_path('storage/images/thumbnails/');
            if ($product->thumbnail == asset("assets/images/noimage.png") && $product->photo != asset("assets/images/noimage.png")) {
                $product->thumbnail = $product->photo;
                if ($product->update()) {
                    $img_dir = public_path() . '/storage/images/products/' . $product->photo;
                    $thumb_path .= $product->photo;
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
            $msg = $updated . " " . __('Thumbnails successfully updated!');
            $alert = false;
        } else {
            $msg = __('There is no thumbnails to update!');
            $alert = true;
        }
        return response()->json(['status' => true, 'message' => $msg, 'alert' => $alert]);
    }
}
