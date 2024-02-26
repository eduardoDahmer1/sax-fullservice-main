<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Reply;
use App\Models\Rating;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Pagesetting;
use App\Models\Subcategory;
use App\Models\ProductClick;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\CategoryGallery;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class CatalogController extends Controller
{
    // CATEGORIES SECTOPN

    public function categories()
    {
        $allCategories = Category::with('subs_order_by.childs_order_by')->orderBy('slug')->where('status', 1)->get();
        return view('front.categories', compact('allCategories'));
    }

    public function brands()
    {
        // get unique first letter from brands to use in alphabetical sort
        $chars = Brand::selectRaw('LEFT(UPPER(name),1) AS fl')->orderBy('fl')->groupBy('fl')->pluck('fl');

        $brands = Brand::where('status', 1)->withCount(['products' => function ($query) {
            $query->byStore();
        }])->orderBy('name')->get();

        return view('front.brands', compact('chars', 'brands'));
    }

    public function brand(Request $request, string $slug)
    {
        $data = [];

        $brand = Brand::where('slug', $slug)->firstOrFail();
        $data['brand'] = $brand;

        $qty = $request->qty;
        $sort = $request->sort;
        $data['sort'] = $sort;

        $prods = Product::byStore()
      ->where('brand_id', $brand->id)
      ->where('status', '=', 1)
      ->orderByRaw("(stock > 0 or stock is null) DESC")
      ->onlyFatherProducts()
      ->when(!$this->storeSettings->show_products_without_stock, fn($query) => $query->withStock())
      ->when($sort, function ($query, $sort) {
          if ($sort == 'date_desc') {
              return $query->orderBy('id', 'DESC');
          } elseif ($sort == 'date_asc') {
              return $query->orderBy('id', 'ASC');
          } elseif ($sort == 'price_desc') {
              return $query->orderBy('price', 'DESC');
          } elseif ($sort == 'price_asc') {
              return $query->orderBy('price', 'ASC');
          } elseif ($sort == 'availability') {
              return $query->orderBy('stock', 'DESC');
          }
      })
      ->when(empty($sort), function ($query) use (&$data) {
          $collumn = config("app.default_sort.collumn");
          $order = config("app.default_sort.order");

          $data['sort'] = config("app.sort")[$collumn][$order];

          return $query->orderBy($collumn, $order);
      })
      ->paginate(isset($qty) ? $qty : 25);


        $data['prods'] = $prods;

        /* Return featured products if there are no products available via Search */
        if ($data['prods']->count() == 0) {
            $homeSettings = Pagesetting::where('store_id', $this->storeSettings->id)->first();
            if ($this->storeSettings->show_products_without_stock) {
                $feature_products =  ($homeSettings->random_products == 1 ?
          Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->inRandomOrder()->take(10)->get() :
          Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->orderBy('id', 'desc')->take(10)->get());
            } else {
                $feature_products =  ($homeSettings->random_products == 1 ?
          Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->whereRaw('(stock > 0 or stock is null)')->inRandomOrder()->take(10)->get() :
          Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->whereRaw('(stock > 0 or stock is null)')->orderBy('id', 'desc')->take(10)->get());
            }
            $data['feature_products'] = $feature_products;
        }

        $data['qty'] = $qty;

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $data['curr'] = $curr;
        $data['first_curr'] = $first_curr;

        if ($request->ajax()) {
            $data['ajax_check'] = 1;
            return view('includes.product.filtered-products', $data);
        }

        return view('front.brand', $data);
    }

    // -------------------------------- CATEGORY SECTION ----------------------------------------

    public function category(Request $request, $slug = null, $slug1 = null, $slug2 = null)
    {
        $cat = null;
        $subcat = null;
        $childcategory = null;
        $minprice = $request->min;
        $maxprice = $request->max;

        $minprice = ($minprice * 100) / (100 + $this->storeSettings->product_percent);
        $maxprice = ($maxprice * 100) / (100 + $this->storeSettings->product_percent);

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }

        $first_curr = Currency::where('id', '=', 1)->first();
        $data['curr'] = $curr;
        $data['first_curr'] = $first_curr;
        $minprice = $minprice / $curr->value;
        $maxprice = $maxprice / $curr->value;

        $qty = $request->qty;
        $sort = $request->sort ?? 
            config("app.sort")[config("app.default_sort.collumn")][config("app.default_sort.order")];
        $searchHttp = $request->searchHttp;
        $data['sort'] = $sort;

        $brand = $request->brand;

        if (!empty($slug)) {
            $cat = Category::where('slug', $slug)->where('status', 1)->firstOrFail();
            $data['cat'] = $cat;
        }
        if (!empty($slug1)) {
            $subcat = Subcategory::where('slug', $slug1)->where('status', 1)->firstOrFail();
            $data['subcat'] = $subcat;
        }
        if (!empty($slug2)) {
            $childcategory = Childcategory::where('slug', $slug2)->where('status', 1)->firstOrFail();
            $data['childcategory'] = $childcategory;
        }
        if (!empty($brand)) {
            $brand = Brand::where('slug', $brand)->firstOrFail();
            $data['brand'] = $brand;
        }
        if (config("features.marketplace")) {
            $prods = Product::byStore()
                        ->where('status', 1)
                        ->where('being_sold', 1);
        } else {
            $prods = Product::byStore()->where('status', '=', 1);
        }

        $prods->orderByRaw("(stock > 0 or stock is null) DESC");

        $prods->when($cat, function ($query, $cat) {
            return $query->where('category_id', $cat->id);
        })
      ->when($subcat, function ($query, $subcat) {
          return $query->where('subcategory_id', $subcat->id);
      })
      ->when($childcategory, function ($query, $childcategory) {
          return $query->where('childcategory_id', $childcategory->id);
      })
      ->when($brand, function ($query, $brand) {
          return $query->where('brand_id', $brand->id);
      })
      ->when($searchHttp, function ($query, $searchHttp) {
          $searchLocale = $this->storeLocale->locale;
          if (Session::has('language') && $this->storeSettings->is_language) {
              $searchLocale = Language::find(Session::get('language'))->locale;
          }
          $terms = str_replace(' ', '%', implode(' ', array_reverse(explode(' ', $searchHttp))));
          $searchHttp = str_replace(' ', '%', trim($searchHttp));

          if (!config("features.marketplace")) {
              return $query->where(function ($query) use ($searchHttp, $terms, $searchLocale) {
                  $query->where('sku', 'like', "%{$searchHttp}%")
                ->orWhere('ref_code', 'like', "%{$searchHttp}%")
                  ->orWhereTranslationLike('name', "%{$searchHttp}%", $searchLocale)
                  ->orWhereTranslationLike('name', "%{$terms}%", $searchLocale)
                  ->orWhereTranslationLike('features', "%{$searchHttp}%", $searchLocale);
              });
          } else {
              return $query->where(function ($query) use ($searchHttp, $terms, $searchLocale) {
                  $query->whereTranslationLike('name', "%{$searchHttp}%", $searchLocale)
              ->orWhereTranslationLike('name', "%{$searchHttp}%", $searchLocale);
              });
          }
      })
      ->when($minprice, function ($query, $minprice) {
          return $query->where('price', '>=', $minprice);
      })
      ->when($maxprice, function ($query, $maxprice) {
          return $query->where('price', '<=', $maxprice);
      })
        ->when($sort, function ($query, $sort) {
            foreach (config("app.sort") as $collumn => $options) {
                foreach ($options as $order => $option) {
                    if ($sort === $option) {
                        return $collumn === 'name' ? 
                            $query->orderByTranslation($collumn, $order) :
                            $query->orderBy($collumn, $order);
                    }
                }
            }
        });

        $prods = $prods->where(function ($query) use ($cat, $subcat, $childcategory, $request) {
            $flag = 0;



            if (!empty($cat)) {
                if (!empty($cat->attributes)) {
                    foreach ($cat->attributes as $key => $attribute) {
                        $inname = $attribute->input_name;
                        $catFilters = $request["$inname"];
                        if (!empty($catFilters)) {
                            $flag = 1;
                            foreach ($catFilters as $key => $catFilter) {
                                if ($key == 0) {
                                    $query->where('attributes', 'like', '%' . '"' . $catFilter . '"' . '%');
                                } else {
                                    $query->orWhere('attributes', 'like', '%' . '"' . $catFilter . '"' . '%');
                                }
                            }
                        }
                    }
                }
                // Getting sub-category attributes
                foreach ($cat->subs as $key => $subAttr) {
                    if (!empty($subAttr->attributes)) {
                        $subNames = $subAttr->attributes;
                        foreach ($subNames as $subName) {
                            $subAttrname = $subName->input_name;
                            $subFilters = $request["$subAttrname"];
                            if (!empty($subFilters)) {
                                $flag = 1;
                                foreach ($subFilters as $key => $subFilter) {
                                    if ($key == 0) {
                                        $query->where('attributes', 'like', '%' . '"' . $subFilter . '"' . '%');
                                    } else {
                                        $query->orWhere('attributes', 'like', '%' . '"' . $subFilter . '"' . '%');
                                    }
                                }
                            }
                        }
                    }
                }

                // Getting child-category attributes
                foreach ($cat->childs as $key => $childAttr) {
                    $childNames = $childAttr->attributes;
                    foreach ($childNames as $childName) {
                        $childAttrName = $childName->input_name;
                        $childFilters = $request["$childAttrName"];
                        if (!empty($childFilters)) {
                            $flag = 1;
                            foreach ($childFilters as $key => $childFilter) {
                                if ($key == 0) {
                                    $query->where('attributes', 'like', '%' . '"' . $childFilter . '"' . '%');
                                } else {
                                    $query->orWhere('attributes', 'like', '%' . '"' . $childFilter . '"' . '%');
                                }
                            }
                        }
                    }
                }
            }


            if (!empty($subcat)) {
                foreach ($subcat->attributes as $attribute) {
                    $inname = $attribute->input_name;
                    $chFilters = $request["$inname"];
                    if (!empty($chFilters)) {
                        $flag = 1;
                        foreach ($chFilters as $key => $chFilter) {
                            if ($key == 0 && $flag == 0) {
                                $query->where('attributes', 'like', '%' . '"' . $chFilter . '"' . '%');
                            } else {
                                $query->orWhere('attributes', 'like', '%' . '"' . $chFilter . '"' . '%');
                            }
                        }
                    }
                }
            }


            if (!empty($childcategory)) {
                foreach ($childcategory->attributes as $attribute) {
                    $inname = $attribute->input_name;
                    $chFilters = $request["$inname"];
                    if (!empty($chFilters)) {
                        $flag = 1;
                        foreach ($chFilters as $key => $chFilter) {
                            if ($key == 0 && $flag == 0) {
                                $query->where('attributes', 'like', '%' . '"' . $chFilter . '"' . '%');
                            } else {
                                $query->orWhere('attributes', 'like', '%' . '"' . $chFilter . '"' . '%');
                            }
                        }
                    }
                }
            }
        });

        $range_max = $prods->max('price') * $curr->value * (1 + ($this->storeSettings->product_percent / 100)) * 1.5;
        $range_max = ceil($range_max / 1000) * 1000;
        $data['range_max'] = $range_max;

        if (!$this->storeSettings->show_products_without_stock) {
            $prods->withStock();
        }

        $prods = $prods->onlyFatherProducts()->paginate(isset($qty) ? $qty : 25);

        $data['prods'] = $prods;

        /* Return featured products if there are no products available via Search */
        if ($data['prods']->count() == 0) {
            $homeSettings = Pagesetting::where('store_id', $this->storeSettings->id)->first();
            if ($this->storeSettings->show_products_without_stock) {
                $feature_products =  ($homeSettings->random_products == 1 ?
                    Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->onlyFatherProducts()->inRandomOrder()->take(10)->get() :
                    Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->onlyFatherProducts()->orderBy('id', 'desc')->take(10)->get());
            } else {
                $feature_products =  ($homeSettings->random_products == 1 ?
                    Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->onlyFatherProducts()->withStock()->inRandomOrder()->take(10)->get() :
                    Product::byStore()->where('featured', '=', 1)->where('status', '=', 1)->onlyFatherProducts()->withStock()->orderBy('id', 'desc')->take(10)->get());
            }
            $data['feature_products'] = $feature_products;
        }
        if ($cat || $subcat || $childcategory) {
            $data['banner'] = ($childcategory->banner_link) ?? ($subcat->banner_link) ?? ($cat->banner_link);
        }
        if (!$cat) {
            $data['banner'] = null;
        }

        $data['categories'] = Category::with('subs_order_by.childs_order_by')->orderBy('slug')->where('status', 1)->get();

        $data['brands'] = Brand::where('status', true)->whereHas('products', function ($query) use($cat, $subcat, $childcategory) {
            $query->where('products.status', true);
            $query->when($cat, fn () => $query->where('category_id', $cat->id));
            $query->when($subcat, fn () => $query->where('subcategory_id', $subcat->id));
            $query->when($childcategory, fn () => $query->where('childcategory_id', $childcategory->id));
        })->orderBy('name')->get();

        $data['qty'] = $qty;

        if ($request->ajax()) {
            $data['ajax_check'] = 1;

            return view('includes.product.filtered-products', $data);
        }

        return view('front.category', $data);
    }


    public function getsubs(Request $request)
    {
        $category = Category::where('slug', $request->category)->firstOrFail();
        $subcategories = Subcategory::where('category_id', $category->id)->get();
        return $subcategories;
    }


    // -------------------------------- PRODUCT DETAILS SECTION ----------------------------------------

    public function report(Request $request)
    {
        //--- Validation Section
        $rules = [
      'note' => 'max:400',
    ];
        $customs = [
      'note.max' => 'Note Must Be Less Than 400 Characters.',
    ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Report;
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function product($slug)
    {
        if (config("features.marketplace")) {
            if (!Product::where('slug', $slug)->isBeingSold()->first()) {
                return redirect()->route('front.index');
            }
        }
        //admins can see the product page using the link in admin panel, so we check for that
        $isAdmin = false;
        if (request()->has('admin-view') && Auth::guard('admin')->check()) {
            $productt = Product::where('slug', '=', $slug)->firstOrFail();
            $isAdmin = true;
        } else {
            $productt = Product::byStore()->where('slug', '=', $slug)->where('status', '=', 1)->firstOrFail();
        }
        $productt->views += 1;
        $productt->update();
        if (Session::has('currency')) {
            $product_curr = Currency::find(Session::get('currency'));
        } else {
            $product_curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $product_click =  new ProductClick;
        $product_click->product_id = $productt->id;
        $product_click->date = Carbon::now()->format('Y-m-d');
        $product_click->save();
        if ($productt->user_id != 0) {
            $vendors = Product::byStore()->where('status', '=', 1)->where('user_id', '=', $productt->user_id)->take(8)->get();
        } else {
            $vendors = Product::byStore()->where('status', '=', 1)->where('user_id', '=', 0)->take(8)->get();
        }
        $ftp_path = public_path('storage/images/ftp/'.$this->storeSettings->ftp_folder.$productt->ref_code_int.'/');
        $ftp_gallery=[];
        if (File::exists($ftp_path)) {
            $files = scandir($ftp_path);
            $extensions = array('.jpg','.jpeg','.gif','.png');
            foreach ($files as $file) {
                $file_extension = strtolower(strrchr($file, '.'));
                if (in_array($file_extension, $extensions) === true) {
                    $ftp_gallery[]=asset('storage/images/ftp/'.$this->storeSettings->ftp_folder.$productt->ref_code_int.'/'.$file);
                }
            }
        }

        $category_gallery = CategoryGallery::where('category_id', '=', $productt->category_id)->get();

        foreach ($category_gallery as $gallery) {
            // Existing categories will ever have thumbnail == null after migration
            if ($gallery->thumbnail == null) {
                // Checks if thumbnail already exists in thumbnails folder
                if (!file_exists(public_path().'/storage/images/thumbnails/'.$gallery->customizable_gallery)) {
                    $gallery->thumbnail = $gallery->customizable_gallery;
                    // If update has success setting thumbnail = customizable_gallery (refers to image names)
                    if ($gallery->update()) {
                        $img_dir = public_path().'/storage/images/galleries/'.$gallery->customizable_gallery;
                        $thumb_dir = public_path().'/storage/images/thumbnails/'.$gallery->customizable_gallery;
                        $img = Image::make($img_dir);
                        $thumb = $img->resize(285, 285);
                        $thumb->save($thumb_dir);
                    }
                }
            }
        }
        if ($productt->color_gallery) {
            $color_gallery = explode(",", $productt->color_gallery);
        } else {
            $color_gallery = null;
        }


        if ($productt->material_gallery) {
            $material_gallery = explode(",", $productt->material_gallery);
        } else {
            $material_gallery = null;
        }

        /* Set related products by Child Category, if available. Then, by Sub Category, if available. Use Category as fallback. */
        $related_by = ($productt->childcategory_id ? $productt->childcategory : ($productt->subcategory_id ? $productt->subcategory : $productt->category));

        $related_products = $related_by->products()->byStore()->where('status', '=', 1)
            ->where('id', '!=', $productt->id)
            ->onlyFatherProducts()
            ->when(!$this->storeSettings->show_products_without_stock, fn($query) => $query->withStock())
            ->take(8)->get();

        // Material stock
        if (!empty($productt->material_qty)) {
            foreach ($productt->material_qty as $product_material_stock) {
                if ($product_material_stock > 0) {
                    $material_stock = $product_material_stock;
                    break;
                }
            }
        } else {
            $material_stock =  $productt->stock;
        }

        return view('front.product', compact('productt', 'product_curr', 'first_curr', 'vendors', 'isAdmin', 'ftp_gallery', 'category_gallery', 'color_gallery', 'material_gallery', 'related_products', 'material_stock'));
    }

    public function quick($id)
    {
        $product = Product::findOrFail($id);
        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        return view('load.quick', compact('product', 'curr', 'first_curr'));
    }

    public function affProductRedirect($slug)
    {
        $product = Product::where('slug', '=', $slug)->where('status', '=', 1)->first();
        //        $product->views+=1;
        //        $product->update();


        return redirect($product->affiliate_link);
    }
    // -------------------------------- PRODUCT DETAILS SECTION ENDS----------------------------------------



    // -------------------------------- PRODUCT COMMENT SECTION ----------------------------------------

    public function comment(Request $request)
    {
        $comment = new Comment;
        $input = $request->all();
        $comment->fill($input)->save();
        $comments = Comment::where('product_id', '=', $request->product_id)->get()->count();
        $data[0] = $comment->user->photo ? url('storage/images/users/' . $comment->user->photo) : url('assets/images/noimage.png');
        $data[1] = $comment->user->name;
        $data[2] = $comment->created_at->diffForHumans();
        $data[3] = $comment->text;
        $data[4] = $comments;
        $data[5] = route('product.comment.delete', $comment->id);
        $data[6] = route('product.comment.edit', $comment->id);
        $data[7] = route('product.reply', $comment->id);
        $data[8] = $comment->user->id;
        $data["reply"] = __("Reply");
        $data["edit"] = __("Edit");
        $data["delete"] = __("Delete");
        $data["edit_comment"] = __("Edit Your Comment");
        $data["submit"] = __("Submit");
        $data["cancel"] = __("Cancel");
        $data["write_reply"] = __("Write your reply");
        return response()->json($data);
    }

    public function commentedit(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->text = $request->text;
        $comment->update();
        return response()->json($comment->text);
    }

    public function commentdelete($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->replies->count() > 0) {
            foreach ($comment->replies as $reply) {
                $reply->delete();
            }
        }
        $comment->delete();
    }

    // -------------------------------- PRODUCT COMMENT SECTION ENDS ----------------------------------------

    // -------------------------------- PRODUCT REPLY SECTION ----------------------------------------

    public function reply(Request $request, $id)
    {
        $reply = new Reply;
        $input = $request->all();
        $input['comment_id'] = $id;
        $reply->fill($input)->save();
        $data[0] = $reply->user->photo ? url('storage/images/users/' . $reply->user->photo) : url('assets/images/noimage.png');
        $data[1] = $reply->user->name;
        $data[2] = $reply->created_at->diffForHumans();
        $data[3] = $reply->text;
        $data[4] = route('product.reply.delete', $reply->id);
        $data[5] = route('product.reply.edit', $reply->id);
        $data["reply"] = __("Reply");
        $data["edit"] = __("Edit");
        $data["delete"] = __("Delete");
        $data["edit_reply"] = __("Edit Your Reply");
        $data["submit"] = __("Submit");
        $data["cancel"] = __("Cancel");
        return response()->json($data);
    }

    public function replyedit(Request $request, $id)
    {
        $reply = Reply::findOrFail($id);
        $reply->text = $request->text;
        $reply->update();
        return response()->json($reply->text);
    }

    public function replydelete($id)
    {
        $reply = Reply::findOrFail($id);
        $reply->delete();
    }

    // -------------------------------- PRODUCT REPLY SECTION ENDS----------------------------------------


    // ------------------ Rating SECTION --------------------

    public function reviewsubmit(Request $request)
    {
        $ck = 0;
        $orders = Order::where('user_id', '=', $request->user_id)->where('status', '=', 'completed')->get();

        if (!config("features.marketplace")) {
            foreach ($orders as $order) {
                $cart = $order->cart;
                foreach ($cart['items'] as $product) {
                    if ($request->product_id == $product['item']['id']) {
                        $ck = 1;
                        break;
                    }
                }
            }
        } else {
            $ck = 1;
        }
        if ($ck == 1) {
            $user = Auth::guard('web')->user();
            $prev = Rating::where('product_id', '=', $request->product_id)->where('user_id', '=', $user->id)->get();
            if (count($prev) > 0) {
                return response()->json(array('errors' => [0 => __('You Have Reviewed Already.')]));
            }
            $Rating = new Rating;
            $Rating->fill($request->all());
            $Rating['review_date'] = date('Y-m-d H:i:s');
            $Rating->save();
            $data[0] = __('Your Rating Submitted Successfully.');
            $data[1] = Rating::rating($request->product_id);
            return response()->json($data);
        } else {
            return response()->json(array('errors' => [0 => __('Buy This Product First')]));
        }
    }


    public function reviews($id)
    {
        $productt = Product::find($id);
        return view('load.reviews', compact('productt', 'id'));
    }

    // ------------------ Rating SECTION ENDS --------------------
}
