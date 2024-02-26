<?php

namespace App\Models;

use App\Enums\AssociationType;
use stdClass;
use App\Models\Currency;
use App\Models\Generalsetting;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends LocalizedModel
{
    use LogsActivity;


    protected $storeSettings;
  

    protected $with = ['translations'];

    public $translatedAttributes = [
        'name',
        'details',
        'ship',
        'policy',
        'meta_tag',
        'meta_description',
        'features',
        'tags',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'product_type',
        'affiliate_link',
        'sku',
        'gtin',
        'brand_id',
        'subcategory_id',
        'childcategory_id',
        'attributes',
        'photo',
        'size',
        'size_qty',
        'size_price',
        'product_size',
        'color',
        'price',
        'previous_price',
        'stock',
        'status',
        'views',
        'featured',
        'best',
        'top',
        'hot',
        'show_in_navbar',
        'latest',
        'big',
        'trending',
        'sale',
        'colors',
        'product_condition',
        'youtube',
        'type',
        'file',
        'license',
        'license_qty',
        'link',
        'platform',
        'region',
        'licence_type',
        'measure',
        'discount_date',
        'is_discount',
        'whole_sell_qty',
        'whole_sell_discount',
        'catalog_id',
        'slug',
        'ref_code',
        'ref_code_int',
        'mpn',
        'free_shipping',
        'max_quantity',
        'weight',
        'width',
        'height',
        'length',
        'external_name',
        'color_qty',
        'color_price',
        'being_sold',
        'vendor_min_price',
        'vendor_max_price',
        'color_gallery',
        'material',
        'material_gallery',
        'material_qty',
        'material_price',
        'show_price',
        'mercadolivre_category_attributes',
        'mercadolivre_name',
        'mercadolivre_description',
        'mercadolivre_id',
        'mercadolivre_listing_type_id',
        'mercadolivre_price',
        'mercadolivre_warranty_type_id',
        'mercadolivre_warranty_type_name',
        'mercadolivre_warranty_time',
        'mercadolivre_warranty_time_unit',
        'mercadolivre_without_warranty',
        'promotion_price',
    ];

    public function __construct()
    {
        $this->storeSettings = resolve('storeSettings');

        parent::__construct();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('products')
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['views'])
            ->dontSubmitEmptyLogs();
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public static function filterProducts($collection)
    {
        foreach ($collection as $key => $data) {
            if ($data->user_id != 0) {
                if ($data->user->is_vendor != 2) {
                    unset($collection[$key]);
                }
            }
            if (isset($_GET['max'])) {
                if ($data->vendorSizePrice() >= $_GET['max']) {
                    unset($collection[$key]);
                }
            }
            $data->price = $data->vendorSizePrice();
        }
        return $collection;
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
    public function associatedProductsByColor()
    {
        return $this->associatedProducts()->wherePivot('association_type', AssociationType::Color);
    }

    public function associatedProductsBySize()
    {
        return $this->associatedProducts()->wherePivot('association_type', AssociationType::Size);
    }

    public function associatedProductsByLook()
    {
        return $this->associatedProducts()->wherePivot('association_type', AssociationType::Look);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function childcategory()
    {
        return $this->belongsTo('App\Models\Childcategory')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }

    public function pickups()
    {
        return $this->belongsToMany(Pickup::class);
    }

    public function galleries360()
    {
        return $this->hasMany('App\Models\Gallery360');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function associatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'associated_products', 'product_id', 'associated_product_id');
    }

    public function fatherProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'associated_products', 'associated_product_id', 'product_id');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report', 'user_id');
    }

    public function stores()
    {
        return $this->belongsToMany('App\Models\Generalsetting', 'product_store', 'product_id', 'store_id');
    }

    public function scopeByStore($query)
    {
        return $query->whereHas('stores', function ($query) {
            $query->where('store_id', $this->storeSettings->id);
        });
    }

    public function scopeIsActive($query)
    {
        return $query->where('products.status', 1);
    }

    public function scopeIsBeingSold($query)
    {
        return $query->where('products.being_sold', 1);
    }

    public function vendorPrice()
    {
        $price = $this->price;
        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }
        $price += $price * (($this->storeSettings->product_percent) / 100);
        return $price;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () => filter_var($this->photo, FILTER_VALIDATE_URL) 
                ? $this->photo 
                : asset('storage/images/products/' . $this->photo),
        );
    }

    public function vendorSizePrice()
    {
        $price = $this->price;
        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }
        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    $price += $this->size_price[$key];
                    break;
                }
            }
        }

        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    $price += $this->color_price[$key];
                    break;
                }
            }
            //$price += $this->color_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }


        // Attribute Section Ends
        $price += $price * (($this->storeSettings->product_percent) / 100);
        return $price;
    }


    public function setCurrency()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }

        $price = $this->price;
        if (Session::has('currency') && $this->storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $price = round($price * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }
    public function setCurrencyFirst()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }
        if ($this->storeSettings->currency_id == 1) {
            return '';
        }

        $price = $this->price;

        $curr = Currency::where('id', '=', 1)->first();

        $price = round($price * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function firstCurrencyPrice()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }

        // if ($this->price > $this->promotion_price && $this->promotion_price > 0)  {
        //     $price = $this->promotion_price;
        //     if ($this->user_id != 0) {
        //         $price = $this->promotion_price + $this->storeSettings->fixed_commission + ($this->promotion_price / 100) * $this->storeSettings->percentage_commission;
        //     }
        // }else{
            $price = $this->price; 

        //     if ($this->user_id != 0) {
        //         $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        //     }
        // }

        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }

        if (!empty($this->size) && isset($this->size_price[0])) {
            $price += $this->size_price[0];
        }
        if (!empty($this->color) && isset($this->color_price[0])) {
            $price += $this->color_price[0];
        }
        if (!empty($this->material) && isset($this->material_price[0])) {
            $price += $this->material_price[0];
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }

        // Attribute Section Ends

        $curr = Currency::where('id', '=', 1)->first();

        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = round(($price) * $curr->value, 2);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
 


        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showVendorMinPrice()
    {
        $price = $this->vendor_min_price;

        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }

        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    $price += $this->size_price[$key];
                    break;
                }
            }
        }
        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    $price += $this->color_price[$key];
                    break;
                }
            }
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }


        // Attribute Section Ends


        if (Session::has('currency') && $this->storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }



        $price = round(($price) * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showVendorMaxPrice()
    {
        $price = $this->vendor_max_price;

        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }

        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    $price += $this->size_price[$key];
                    break;
                }
            }
        }
        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    $price += $this->color_price[$key];
                    break;
                }
            }
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }


        // Attribute Section Ends


        if (Session::has('currency') && $this->storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }



        $price = round(($price) * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showVendorPrice()
    {
        $price = $this->price;

        if ($this->user_id != 0) {
            $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
        }

        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    $price += $this->size_price[$key];
                    break;
                }
            }
        }
        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    $price += $this->color_price[$key];
                    break;
                }
            }
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }


        // Attribute Section Ends


        if (Session::has('currency') && $this->storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }



        $price = round(($price) * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showPrice()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }

        if ($this->price > $this->promotion_price && $this->promotion_price > 0)  {
            $price = $this->promotion_price;
            if ($this->user_id != 0) {
                $price = $this->promotion_price + $this->storeSettings->fixed_commission + ($this->promotion_price / 100) * $this->storeSettings->percentage_commission;
            }
        }else{
            $price = $this->price; 

            if ($this->user_id != 0) {
                $price = $this->price + $this->storeSettings->fixed_commission + ($this->price / 100) * $this->storeSettings->percentage_commission;
            }
        }


        if (!empty($this->size)) {
            foreach ($this->size as $key => $size) {
                if ($this->size_qty[$key] > 0) {
                    $price += $this->size_price[$key];
                    break;
                }
            }
        }
        if (!empty($this->color)) {
            foreach ($this->color as $key => $color) {
                if ($this->color_qty[$key] > 0) {
                    $price += $this->color_price[$key];
                    break;
                }
            }
        }
        if (!empty($this->material)) {
            foreach ($this->material as $key => $material) {
                if ($this->material_qty[$key] > 0) {
                    $price += $this->material_price[$key];
                    break;
                }
            }
        }

        // Attribute Section

        $attributes = $this->attributes["attributes"];
        if (!empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (!empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }


        // Attribute Section Ends


        if (Session::has('currency') && $this->storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }



        $price = round(($price) * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
     
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showPreviousPrice()
    {
        if (!$this->storeSettings->show_product_prices || !$this->show_price) {
            return '';
        }

        $price = $this->previous_price;
        if (!$price) {
            return '';
        }
        if ($this->user_id != 0) {
            $price = $this->previous_price + $this->storeSettings->fixed_commission + ($this->previous_price / 100) * $this->storeSettings->percentage_commission;
        }

        //     if(!empty($this->size)){
        //         $price += $this->size_price[0];
        //     }

        // // Attribute Section

        // $attributes = $this->attributes["attributes"];
        //   if(!empty($attributes)) {
        //       $attrArr = json_decode($attributes, true);
        //   }
        //   // dd($attrArr);
        //   if (!empty($attrArr)) {
        //       foreach ($attrArr as $attrKey => $attrVal) {
        //         if (is_array($attrVal) && array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1) {

        //             foreach ($attrVal['values'] as $optionKey => $optionVal) {
        //               $price += $attrVal['prices'][$optionKey];
        //               // only the first price counts
        //               break;
        //             }

        //         }
        //       }
        //   }


        // Attribute Section Ends


        if (Session::has('currency') && $this->storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $price = round($price * $curr->value, 2);
        //Add product_percent on price
        $price += $price * (($this->storeSettings->product_percent) / 100);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function signfirstPrice($price)
    {
        $storeSettings = resolve('storeSettings');
        if ($storeSettings->currency_id == 1) {
            return '';
        }
        $curr = Currency::where('id', '=', 1)->first();
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function convertPriceReverse($price)
    {
        $storeSettings = resolve('storeSettings');
        if (Session::has('currency') && $storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $price = round($price / $curr->value, 2);
        $price = number_format($price, $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $first_curr->sign . $price;
        } else {
            return $price . $first_curr->sign;
        }
    }

    public static function convertPrice($price)
    {
        $storeSettings = resolve('storeSettings');
        if (Session::has('currency') && $storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($storeSettings->currency_id);
        }
        $price = round($price * $curr->value, 2);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function convertPriceDolar($price)
    {
        $storeSettings = resolve('storeSettings');
        if (Session::has('currency') && $storeSettings->is_currency) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($storeSettings->currency_id);
        }

        $valor_formatado = number_format($price, 2, '.', ',');
        
        if ($storeSettings->currency_format == 0) {
            return 'U$ ' . $valor_formatado;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function vendorConvertPrice($price)
    {
        $storeSettings = resolve('storeSettings');
        $curr = $curr = Currency::find($storeSettings->currency_id);
        $price = round($price * $curr->value, 2);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public static function convertPreviousPrice($price)
    {
        $storeSettings = resolve('storeSettings');

        $curr = $curr = Currency::find($storeSettings->currency_id);
        $price = round($price * $curr->value, 2);
        $price = number_format($price, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        if ($storeSettings->currency_format == 0) {
            return $curr->sign . $price;
        } else {
            return $price . $curr->sign;
        }
    }

    public function showName()
    {
        $name = mb_strlen($this->name, 'utf-8') > 55 ? mb_substr($this->name, 0, 55, 'utf-8') . '...' : $this->name;
        return $name;
    }


    public function emptyStock()
    {
        return $this->withStock()->count() === 0;
    }

    public static function showTags()
    {
        $tags = null;
        $tagz = '';
        $name = Product::where('status', '=', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            if (!empty($nm)) {
                foreach ($nm as $n) {
                    $tagz .= $n . ',';
                }
            }
        }
        $tags = array_unique(explode(',', $tagz));
        return $tags;
    }


    public function getSizeAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaxQuantityAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return $value;
    }

    public function getSizeQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorPriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaterialQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaterialPriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMaterialAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if ($value == null) {
            return '';
        }
        return explode(',', $value);
    }

    public function getSlugAttribute($value)
    {
        return $value ? $value : 'noslugdefined';
    }

    public function getRefCodeIntAttribute($value)
    {
        return ($value == 0 ? $this->ref_code : $value);
    }

    public function getPhotoAttribute($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            if (!$value) {
                if ($this->storeSettings->ftp_folder) {
                    if ($value) {
                        return asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/' . $value);
                    }
                    $ftp_path = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/');
                    if (File::exists($ftp_path)) {
                        $files = scandir($ftp_path);
                        $extensions = array('.jpg', '.jpeg', '.gif', '.png');
                        foreach ($files as $file) {
                            $file_extension = strtolower(strrchr($file, '.'));
                            if (in_array($file_extension, $extensions) === true) {
                                return asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/' . $file);
                            }
                        }
                    }
                    return asset('assets/images/noimage.png');
                }
                return asset('assets/images/noimage.png');
            }
            if (File::exists(public_path('storage/images/products/' . $value))) {
                return $value;
            }
            if (!File::exists(public_path('storage/images/products/' . $value))) {
                if (Auth::guard('admin')->check()) {
                    Product::where('id', $this->id)->update(['photo' => null]);
                }
                return asset('assets/images/noimage.png');
            }
        }
        return $value;
    }

    public function getThumbnailAttribute($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            if (!$value) {
                $ftp_path = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/');
                if (File::exists($ftp_path)) {
                    $files = scandir($ftp_path);
                    $extensions = array('.jpg', '.jpeg', '.gif', '.png');
                    foreach ($files as $file) {
                        $file_extension = strtolower(strrchr($file, '.'));
                        if (in_array($file_extension, $extensions) === true) {
                            return asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $this->ref_code_int . '/' . $file);
                        }
                    }
                }
                return asset('assets/images/noimage.png');
            }
            if (File::exists(public_path('storage/images/thumbnails/' . $value))) {
                return $value;
            }
            if (!File::exists(public_path('storage/images/thumbnails/' . $value))) {
                if (Auth::guard('admin')->check()) {
                    Product::where('id', $this->id)->update(['thumbnail' => null]);
                }
                return asset('assets/images/noimage.png');
            }
        }
        return $value;
    }
    public function getDiscountPercentAttribute($value)
    {
        if ($this->previous_price != null && $this->previous_price > $this->price) {
            $discount_percent = $this->previous_price -  $this->price;
            $discount_percent = $discount_percent / $this->previous_price;
            $discount_percent = $discount_percent * 100;
            $value = number_format($discount_percent, 0);
            return $value;
        } else {
            return null;
        }
    }

    public static function scopeMercadoLivreProducts($query)
    {
        return $query->whereNotNull('mercadolivre_id');
    }

    /**
     * @param array $redplayData - Separated Redplay data
     * @return array $redplayArray - All-in-one Redplay data
     */
    public static function sanitizeRedplayData(array $redplayData)
    {
        if (!isset($redplayData['redplay_login'])) {
            return null;
        }

        if (!isset($redplayData['redplay_password'])) {
            return null;
        }

        if (!isset($redplayData['redplay_code'])) {
            return null;
        }

        $redPlayArray = [];

        for ($i = 0; $i < sizeof($redplayData['redplay_login']); $i++) {
            $redplayObject = new stdClass;
            $redplayObject->login = $redplayData['redplay_login'][$i];
            $redplayObject->password = $redplayData['redplay_password'][$i];
            $redplayObject->code = $redplayData['redplay_code'][$i];

            array_push($redPlayArray, (array) $redplayObject);
        }

        return $redPlayArray;
    }

    /**
     * Bulk Edit Change Price Logic
     */
    public function applyBulkEditChangePrice(string $action, $newPrice)
    {
        if (!$newPrice) {
            return $this->price;
        }

        $changePriceTypes = [
            "set_price" => "setFixedPrice",
            "add_percentage" => "addPricePercentage",
            "decrease_percentage" => "decreasePricePercentage",
            "add_price" => "addPriceValue",
            "decrease_price" => "decreasePriceValue"
        ];

        foreach ($changePriceTypes as $priceTypeAction => $function) {
            if ($priceTypeAction === $action) {
                return $this->$function((float) $newPrice);
            }
        }
    }

    private function setFixedPrice(float $newPrice)
    {
        return $newPrice;
    }

    private function addPricePercentage(float $percentage)
    {
        return $this->price + (($percentage / 100) * $this->price);
    }

    private function decreasePricePercentage(float $percentage)
    {
        return $this->price - (($percentage / 100) * $this->price);
    }

    private function addPriceValue(float $newPrice)
    {
        return $this->price + $newPrice;
    }

    private function decreasePriceValue(float $newPrice)
    {
        return $this->price - $newPrice;
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStock($query)
    {
        return $query->whereRaw('(stock > 0 or stock is null)')->orWhereHas('associatedProducts', function (Builder $query) {
            $query->whereRaw('(stock > 0 or stock is null)')->where('associated_products.association_type', AssociationType::Size);
        });
    }

    public function is_available_to_buy()
    {
        return $this->storeSettings->is_cart_and_buy_available && $this->stock > 0;
    }

    public function scopeOnlyFatherProducts($query)
    {
        return $query->whereHas('associatedProducts', function (Builder $query) {
            $query->where('association_type', AssociationType::Size);
        })->orWhereDoesntHave('fatherProducts', function (Builder $query) {
            $query->where('association_type', AssociationType::Size);
        });
    }
}
