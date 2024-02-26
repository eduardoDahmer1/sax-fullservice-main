<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Coupon;
use App\Models\AttributeOption;
use App\Models\AexCity;
use App\Models\CategoryGallery;
use App\Models\CustomProduct;
use App\Models\Generalsetting;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!app()->runningInConsole()) {
            if (!$this->storeSettings->is_cart) {
                return app()->abort(404);
            }
        }
    }

    public function cart()
    {
        if (!$this->storeSettings->is_standard_checkout && !$this->storeSettings->is_simplified_checkout) {
            return view('errors.404');
        }

        if (!Session::has('cart')) {
            return view('front.cart');
        }
        if (Session::has('already')) {
            Session::forget('already');
        }
        if (Session::has('coupon')) {
            Session::forget('coupon');
        }
        if (Session::has('coupon_code')) {
            Session::forget('coupon_code');
        }
        if (Session::has('coupon_total')) {
            Session::forget('coupon_total');
        }
        if (Session::has('coupon_total1')) {
            Session::forget('coupon_total1');
        }
        if (Session::has('coupon_percentage')) {
            Session::forget('coupon_percentage');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $products = $cart->items;
        foreach ($cart->items as $prod) {
            $id = $prod['item']['id'];
        }
        $prod = Product::where('id', $id)->first();
        $totalPrice = $cart->totalPrice;
        $mainTotal = $totalPrice;
        $tx = $this->storeSettings->tax;
        $aex_cities = AexCity::orderBy('denominacion')->get();
        if ($tx != 0) {
            $tax = ($totalPrice / 100) * $tx;
            $mainTotal = $totalPrice + $tax;
        }
        return view('front.cart', compact('products', 'totalPrice', 'mainTotal', 'tx', 'prod', 'aex_cities'));
    }

    public function cartview()
    {
        return view('load.cart');
    }

    public function addtocart($id)
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        $prod = Product::where('id', '=', $id)->first();
        if (empty($prod)) {
            return redirect()->route('front.cart');
        }
        // Set Attrubutes

        $keys = '';
        $values = '';
        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }

        // Set Size

        $size = '';
        if (!empty($prod->size)) {
            $size = trim($prod->size[0]);
        }
        $size = str_replace(' ', '-', $size);



        // Set Color

        $color = '';
        if (!empty($prod->color)) {
            foreach ($prod->color as $key => $color) {
                if ($prod->color_qty[$key] > 0) {
                    $color = $prod->color[$key];
                    $prod->price += $prod->color_price[$key];
                    break;
                }
            }
        }
        $color = str_replace('#', '', $color);

        // Set material

        $material = '';
        if (!empty($prod->material)) {
            foreach ($prod->material as $key => $material) {
                if ($prod->material_qty[$key] > 0) {
                    $material = $prod->material[$key];
                    $prod->price += $prod->material_price[$key];
                    break;
                }
            }
        }
        $material = str_replace(' ', '-', $material);

        // Set Custom
        $customizable_gallery = '';
        $customizable_name = '';
        $customizable_number = '';
        $customizable_logo = '';
        $agree_terms = 0;


        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->storeSettings->fixed_commission + ($prod->price/100) * $this->storeSettings->percentage_commission ;
            $prod->price = round($prc, 2);
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        } else {
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        }

        // Set Attribute


        if (!empty($prod->attributes) && $this->storeSettings->attribute_clickable) {
            $attrArr = json_decode($prod->attributes, true);

            $count = count($attrArr);
            $i = 0;
            $j = 0;
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrKey => $attrVal) {
                    if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                        if ($j == $count - 1) {
                            $keys .= $attrKey;
                        } else {
                            $keys .= $attrKey.',';
                        }
                        $j++;

                        foreach ($attrVal['values'] as $optionKey => $optionVal) {
                            $value_name = AttributeOption::find($optionVal)->name;
                            $values .= $value_name . '~';
                            $temp_price = $attrVal['prices'][$optionKey];
                            $temp_price += $temp_price * ($this->storeSettings->product_percent / 100);
                            $prod->price += $temp_price;
                            break;
                        }
                    }
                }
            }
        }
        $keys = rtrim($keys, ',');
        $values = rtrim($values, '~');


        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        $cart->add($prod, $prod->id, $material, $size, $color, $customizable_gallery, $customizable_name, $customizable_number, $customizable_logo, $agree_terms, $keys, $values);

        $custom_item_id = $id.$size.$color.$material.$customizable_gallery.$customizable_name.$customizable_number.$customizable_logo.str_replace(str_split(' ,'), '', $values);
        $custom_item_id = str_replace(array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '', $custom_item_id);

        if ($cart->items[$custom_item_id]['dp'] == 1) {
            return redirect()->route('front.cart');
        }
        if ($cart->items[$custom_item_id]['stock'] < 0) {
            return redirect()->route('front.cart');
        }
        if (!empty($cart->items[$custom_item_id]['max_quantity']) && ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['max_quantity'])) {
            return redirect()->route('front.cart');
        }
        if (!empty($cart->items[$custom_item_id]['size_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['size_qty']) {
                return redirect()->route('front.cart');
            }
        }

        if (!empty($cart->items[$custom_item_id]['material_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['material_qty']) {
                return redirect()->route('front.cart');
            }
        }

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $item) {
            if ($prod->promotion_price > 0) {
                $item['price'] = $prod->promotion_price;
            }
            $cart->totalPrice += $item['price'];
        }

        Session::put('cart', $cart);
        Session::put('is_customizable', false);
        return redirect()->route('front.cart')->with([
            'pixel_id' => $item['item']['id'],
            'pixel_name' => $item['item']['name'],
            'pixel_category' => $item['item']['category']['name'],
            'pixel_price' => $item['price'],
            'pixel_type' => $item['item']['type'],
            'pixel_currency' => $curr['name']
        ]);
    }

    public function addToCartAndRedirectWedding($user, $id)
    {
        $this->addcart($id);

        return redirect()->route('user.wedding.show',$user);
    }

    public function addcart($id)
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        $prod = Product::where('id', '=', $id)->first();
        if (empty($prod)) {
            $data["out_stock"] = __("Out of Stock!");
            $cartError = true;
            return response()->json($data);
        }
        // Set Attrubutes

        $keys = '';
        $values = '';
        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }

        // Set Size

        $size = '';
        if (!empty($prod->size)) {
            $size = trim($prod->size[0]);
        }
        $size = str_replace(' ', '-', $size);


        // Set Color

        $color = '';
        if (!empty($prod->color)) {
            foreach ($prod->color as $key => $color) {
                if ($prod->color_qty[$key] > 0) {
                    $color = $prod->color[$key];
                    $prod->price += $prod->color_price[$key];
                    break;
                }
            }
        }
        $color = str_replace('#', '', $color);

        // Set material
        $material = '';
        if (!empty($prod->material)) {
            foreach ($prod->material as $key => $material) {
                if ($prod->material_qty[$key] > 0) {
                    $material = $prod->material[$key];
                    $prod->price += $prod->material_price[$key];
                    break;
                }
            }
        }
        $material = str_replace(' ', '-', $material);

        // Set Custom
        $customizable_gallery = '';
        $customizable_name = '';
        $customizable_number = '';
        $customizable_logo = '';
        $agree_terms = 0;

        // Vendor Comission

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->storeSettings->fixed_commission + ($prod->price/100) * $this->storeSettings->percentage_commission;
            $prod->price = round($prc, 2);
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        } else {
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        }


        // Set Attribute


        if (!empty($prod->attributes)) {
            $attrArr = json_decode($prod->attributes, true);

            $count = count($attrArr);
            $i = 0;
            $j = 0;
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrKey => $attrVal) {
                    if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                        if ($j == $count - 1) {
                            $keys .= $attrKey;
                        } else {
                            $keys .= $attrKey.',';
                        }
                        $j++;

                        foreach ($attrVal['values'] as $optionKey => $optionVal) {
                            $value_name = AttributeOption::find($optionVal)->name;
                            $values .= $value_name . '~';
                            $temp_price = $attrVal['prices'][$optionKey];
                            $temp_price += $temp_price * ($this->storeSettings->product_percent / 100);
                            $prod->price += $temp_price;
                            break;
                        }
                    }
                }
            }
        }
        $keys = rtrim($keys, ',');
        $values = rtrim($values, '~');

        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        $cartError = false;

        $cart->add($prod, $prod->id, $material, $size, $color, $customizable_gallery, $customizable_name, $customizable_number, $customizable_logo, $agree_terms, $keys, $values);
        $custom_item_id = $id.$size.$color.$material.$customizable_gallery.$customizable_name.$customizable_number.$customizable_logo.str_replace(str_split(' ,'), '', $values);
        $custom_item_id = str_replace(array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '', $custom_item_id);

        if ($cart->items[$custom_item_id]['dp'] == 1) {
            $data["digital"] = __("Already Added To Cart");
            $cartError = true;
        }
        if ($cart->items[$custom_item_id]['stock'] < 0) {
            $data["out_stock"] = __("Out of Stock!");
            $cartError = true;
        }
        if (!empty($cart->items[$custom_item_id]['size_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['size_qty']) {
                $data["out_stock"] = __("Out of Stock!");
                $cartError = true;
            }
        }
        if (!empty($cart->items[$custom_item_id]['color_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['color_qty']) {
                $data["out_stock"] = __("Out of Stock!");
                $cartError = true;
            }
        }
        if (!empty($cart->items[$custom_item_id]['material_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['material_qty']) {
                $data["out_stock"] = __("Out of Stock!");
                $cartError = true;
            }
        }

        if (!empty($cart->items[$custom_item_id]['max_quantity']) && ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['max_quantity'])) {
            $data["out_stock"] = __("Limit Exceeded!");
            $cartError = true;
        }

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }

        if (!$cartError) {
            $cart->totalPrice = 0;
            foreach ($cart->items as $item) {
                if ($item['promotion_price'] > 0) {
                    $item['price'] = $item['promotion_price'];
                }
                $cart->totalPrice += $item['price'];
            }
            Session::put('cart', $cart);
            $data[0] = count($cart->items);
            $data['pixel_id'] = $item['item']['id'];
            $data['pixel_name'] = $item['item']['name'];
            $data['pixel_category'] = $item['item']['category']['name'];
            $data['pixel_price'] = $item['price'];
            $data['pixel_type'] = $item['item']['type'];
            $data['pixel_currency'] = $curr['name'];
            $data["success"] = __("Successfully Added To Cart");
        }
        return response()->json($data);
    }

    public function addnumcart()
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        $id = $_GET['id'];
        $qty = $_GET['qty'];
        $size = str_replace(' ', '-', $_GET['size']);
        $color = $_GET['color'];
        $size_qty = $_GET['size_qty'];
        $size_price = (double)$_GET['size_price'];
        $size_key = $_GET['size_key'];
        $material = $_GET['material'];
        $material_qty = $_GET['material_qty'];
        $material_price = (double)$_GET['material_price'];
        $material_key = $_GET['material_key'];
        $color_qty = $_GET['color_qty'];
        $color_price = (double)$_GET['color_price'];
        $color_key = $_GET['color_key'];
        $keys_arr =  $_GET['keys'];
        $values_arr = $_GET['values'];
        $prices = $_GET['prices'];
        $keys = $keys_arr == "" ? '' :implode(',', $keys_arr);
        $customizable_gallery_src = $_GET['customizable_gallery'];
        $customizable_name = $_GET['customizable_name'];
        $customizable_number = isset($_GET['customizable_number']) ? $_GET['customizable_number'] : null;
        $customizable_gallery_count = isset($_GET['customizable_gallery_count']) ? $_GET['customizable_gallery_count'] : null;
        $customizable_logo = $_GET['customizable_logo'];
        $agree_terms = $_GET['agree_terms'];
        $is_customizable_number = isset($_GET['is_customizable_number']) ? $_GET['is_customizable_number'] : 0;

        //Fixing variable src paht name
        $customizable_gallery = strstr($customizable_gallery_src, 'thumbnails/');
        $customizable_gallery = str_replace("thumbnails/", "", $customizable_gallery);

        $file = str_replace("\\", '/', $customizable_logo);
        $imgName = str_replace("C:/fakepath/", "", $file);
        if (file_exists('storage/images/custom-logo/' . $imgName)) {
            $customizable_logo = $imgName;
        }

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }

        $prod = Product::where('id', '=', $id)->where('status', '=', 1)->first();
        if (empty($prod)) {
            $data["out_stock"] = __("Out of Stock!");
            $cartError = true;
            return response()->json($data);
        }
        if (empty($size_key)) {
            $size_key = 0;
        }
        if (!empty($prod->size_price[$size_key])) {
            $size_price = ($prod->size_price[$size_key]);
            $size_price += $size_price * ($this->storeSettings->product_percent / 100);
            $size = $prod->size[$size_key];
        }

        if (empty($color_key)) {
            $color_key = 0;
        }
        if (!empty($prod->color_price[$color_key])) {
            $color_price = ($prod->color_price[$color_key]);
            $color_price += $color_price * ($this->storeSettings->product_percent / 100);
            $color = $prod->color[$color_key];
        }

        if (empty($material_key)) {
            $material_key = 0;
        }
        if (!empty($prod->material_price[$material_key])) {
            $material_price = ($prod->material_price[$material_key]);
            $material_price += $material_price * ($this->storeSettings->product_percent / 100);
            $material = $prod->material[$material_key];
        }

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->storeSettings->fixed_commission + ($prod->price/100) * $this->storeSettings->percentage_commission ;
            $prc += $prc * ($this->storeSettings->product_percent / 100);
            $prod->price = round($prc, 2);
        } else {
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        }

        if (!empty($prod->attributes)) {
            $attrArr = json_decode($prod->attributes, true);
        }

        $options_prices = 0;
        $index_value = 0;
        $temp_values = array();
        if (!empty($keys_arr)) {
            foreach ($keys_arr as $temp_key) {
                if (!empty($values_arr)) {
                    $optionVal = $attrArr[$temp_key]['values'][$values_arr[$index_value]];
                    $value_name = AttributeOption::find($optionVal)->name;
                    $temp_values[$index_value] = $value_name;
                    $temp_price = $attrArr[$temp_key]['prices'][$values_arr[$index_value]];
                    $temp_price += $temp_price * ($this->storeSettings->product_percent / 100);
                    $options_prices += $temp_price;
                }
                $index_value++;
            }
        }
        $prod->price += $options_prices;
        $values = $temp_values == "" ? '' : implode('~', $temp_values);

        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }
        if (empty($size)) {
            if (!empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);
        }



        if (empty($color)) {
            if (!empty($prod->color)) {
                $color = trim($prod->color[$prod->color_key]);
            }
            $color = str_replace(' ', '-', $color);
        }

        if (empty($material)) {
            if (!empty($prod->material)) {
                $material = trim($prod->material[$prod->material_key]);
            }
            $material = str_replace(' ', '-', $material);
        }


        if (empty($customizable_gallery) || !isset($customizable_gallery)) {
            $customizable_gallery = null;
        }

        if (empty($customizable_name) || !isset($customizable_name)) {
            $customizable_name = null;
        }

        if (empty($customizable_number) || !isset($customizable_number)) {
            $customizable_number = null;
        }

        if (empty($customizable_logo) || !isset($customizable_logo)) {
            $customizable_logo = null;
        }

        if (empty($agree_terms) || !isset($agree_terms)) {
            $agree_terms = 0;
        }

        if (empty($customizable_gallery_count) || !isset($customizable_gallery_count)) {
            $customizable_gallery_count = null;
        }

        $color = str_replace('#', '', $color);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cartError = false;
        $cart->addnum($prod, $prod->id, $qty, $size, $color, $material, $customizable_gallery, $customizable_name, $customizable_number, $customizable_logo, $agree_terms, $size_qty, $size_price, $size_key, $color_qty, $color_price, $color_key, $material_qty, $material_price, $material_key, $keys, $values);
        $custom_item_id = $id.$size.$color.$material.$customizable_gallery.$customizable_name.$customizable_number.$customizable_logo.str_replace(str_split(' ,'), '', $values);
        $custom_item_id = str_replace(array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '', $custom_item_id);

        $customProducts = env("ENABLE_CUSTOM_PRODUCT", false);
        $customProductsNumber = env("ENABLE_CUSTOM_PRODUCT_NUMBER", false);

        if ($customProducts && $customProductsNumber) {
            // Se as duas feature flags estiverem ativadas lança um erro porque não faz sentido.
            // Caso futuramente deva ser implementado algo para tratar isso, é aqui que deverá acontecer.
            // Esse erro nunca vai ser disparado, está aqui apenas para indicar ao desenvolvedor que ambas as flags estão ativas em sua aplicação.
            $data["cart_error"] = __("Application error! Please contact the administrator.");
            $cartError = true;
        }
        if ($customProducts && !$customProductsNumber) {
            if (!empty($cart->items[$custom_item_id]['customizable_gallery']) || !empty($cart->items[$custom_item_id]['customizable_name']) || !empty($cart->items[$custom_item_id]['customizable_logo'])) {
                if ((int)$agree_terms == 0) {
                    $data["agree_terms"] = __("You must signal that you agree with our terms!");
                    $cartError = true;
                }
            }
            if ($customizable_gallery_count > 0) {
                if (empty($customizable_gallery)) {
                    $data["no_texture_selected"] = __("You must select a custom texture!");
                    $cartError = true;
                }
            }
        }
        if ($customProductsNumber && !$customProducts) {
            if ($is_customizable_number) {
                /* if(empty($cart->items[$custom_item_id]['customizable_name']) || empty($cart->items[$custom_item_id]['customizable_number'])){
                    $data["empty_data"] = __("You must fill all the custom product fields.");
                    $cartError = true;
                }
                if(!empty($cart->items[$custom_item_id]['customizable_name']) && !empty($cart->items[$custom_item_id]['customizable_number'])){
                    if((int)$cart->items[$custom_item_id]['customizable_number'] <= 0 || (int)$cart->items[$custom_item_id]['customizable_number'] > 99){
                        $data["empty_data"] = __("Please enter a valid number!");
                        $cartError = true;
                    }
                } */
            }
        }
        if ($cart->items[$custom_item_id]['dp'] == 1) {
            $data["digital"] = __("Already Added To Cart");
            $cartError = true;
        }
        if ($cart->items[$custom_item_id]['stock'] < 0) {
            $data["out_stock"] = __("Out of Stock!");
            $cartError = true;
        }

        if (!empty($cart->items[$custom_item_id]['max_quantity']) && ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['max_quantity'])) {
            $data["out_stock"] = __("Limit Exceeded!");
            $cartError = true;
        }
        if (!empty($cart->items[$custom_item_id]['stock']) && ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['item']['stock'])) {
            $data["out_stock"] = __("Out of Stock!");
            $cartError = true;
        }


        if (!empty($cart->items[$custom_item_id]['size_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['size_qty']) {
                $data["out_stock"] = __("Out of Stock!");
                $cartError = true;
            }
        }

        if (!empty($cart->items[$custom_item_id]['material_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['material_qty']) {
                $data["out_stock"] = __("Out of Stock!");
                $cartError = true;
            }
        }

        if (!empty($cart->items[$custom_item_id]['color_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['color_qty']) {
                $data["out_stock"] = __("Out of Stock!");
                $cartError = true;
            }
        }
        if (!$cartError) {
            $cart->totalPrice = 0;
            foreach ($cart->items as $item) {
                $cart->totalPrice += $item['price'];
            }
            Session::put('cart', $cart);
            $data[0] = count($cart->items);
            $data['pixel_id'] = $item['item']['id'];
            $data['pixel_name'] = $item['item']['name'];
            $data['pixel_category'] = $item['item']['category']['name'];
            $data['pixel_price'] = $item['price'];
            $data['pixel_type'] = $item['item']['type'];
            $data['pixel_currency'] = $curr['name'];
            $data["success"] = __("Successfully Added To Cart");
        }

        return response()->json($data);
    }



    public function addtonumcart()
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        $id = $_GET['id'];
        $qty = $_GET['qty'];
        $size = str_replace(' ', '-', $_GET['size']);
        $color = $_GET['color'];
        $size_qty = $_GET['size_qty'];
        $size_price = (double)$_GET['size_price'];
        $size_key = $_GET['size_key'];
        $material = $_GET['material'];
        $material_qty = $_GET['material_qty'];
        $material_price = (double)$_GET['material_price'];
        $material_key = $_GET['material_key'];
        $color_qty = $_GET['color_qty'];
        $color_price = (double)$_GET['color_price'];
        $color_key = $_GET['color_key'];
        $keys =  $_GET['keys'];
        $keys = explode(",", $keys);
        $values = $_GET['values'];
        $values = explode(",", $values);
        $prices = $_GET['prices'];
        $prices = explode(",", $prices);
        $customizable_gallery_src = $_GET['customizable_gallery'];
        $customizable_name = $_GET['customizable_name'];
        $customizable_number = isset($_GET['customizable_number']) ? $_GET['customizable_number'] : null;
        $customizable_gallery_count = isset($_GET['customizable_gallery_count']) ? $_GET['customizable_gallery_count'] : null;
        $customizable_logo = $_GET['customizable_logo'];
        $agree_terms = $_GET['agree_terms'];
        $is_customizable_number = isset($_GET['is_customizable_number']) ? $_GET['is_customizable_number'] : 0;

        //Fixing variable src paht name
        $customizable_gallery = strstr($customizable_gallery_src, 'thumbnails/');
        $customizable_gallery = str_replace("thumbnails/", "", $customizable_gallery);

        $file = str_replace("\\", '/', $customizable_logo);
        $imgName = str_replace("C:/fakepath/", "", $file);
        if (file_exists('storage/images/custom-logo/' . $imgName)) {
            $customizable_logo = $imgName;
        }

        $prod = Product::where('id', '=', $id)->first();
        if (empty($prod)) {
            return redirect()->route('front.cart');
        }
        if (empty($size_key)) {
            $size_key = 0;
        }
        if (!empty($prod->size_price[$size_key])) {
            $size_price = ($prod->size_price[$size_key]);
            $size_price += $size_price * ($this->storeSettings->product_percent / 100);
            $size = $prod->size[$size_key];
        }

        if (empty($color_key)) {
            $color_key = 0;
        }
        if (!empty($prod->color_price[$color_key])) {
            $color_price = ($prod->color_price[$color_key]);
            $color_price += $color_price * ($this->storeSettings->product_percent / 100);
            $color = $prod->color[$color_key];
        }

        if (empty($material_key)) {
            $material_key = 0;
        }
        if (!empty($prod->material_price[$material_key])) {
            $material_price = ($prod->material_price[$material_key]);
            $material_price += $material_price * ($this->storeSettings->product_percent / 100);
            $material = $prod->material[$material_key];
        }

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->storeSettings->fixed_commission + ($prod->price/100) * $this->storeSettings->percentage_commission ;
            $prod->price = round($prc, 2);
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        } else {
            $prod->price += $prod->price * ($this->storeSettings->product_percent / 100);
        }

        if (!empty($prod->attributes)) {
            $attrArr = json_decode($prod->attributes, true);
        }

        $options_prices = 0;
        $index_value = 0;
        $temp_values = array();
        if (!empty($keys)) {
            foreach ($keys as $temp_key) {
                if (!empty($values) && !empty($temp_key)) {
                    $optionVal = $attrArr[$temp_key]['values'][$values[$index_value]];
                    $value_name = AttributeOption::find($optionVal)->name;
                    $temp_values[$index_value] = $value_name;
                    $temp_price = $attrArr[$temp_key]['prices'][$values[$index_value]];
                    $temp_price += $temp_price * ($this->storeSettings->product_percent / 100);
                    $options_prices += $temp_price;
                }
                $index_value++;
            }
        }
        $keys = $keys == "" ? '' :implode(',', $keys);
        $prod->price += $options_prices;
        $values = $temp_values == "" ? '' : implode('~', $temp_values);

        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return redirect()->route('front.cart');
            }
        }
        if (empty($size)) {
            if (!empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);
        }

        if (empty($color)) {
            if (!empty($prod->color)) {
                $color = trim($prod->color[0]);
            }
            $size = str_replace(' ', '-', $size);
        }

        if (empty($material)) {
            if (!empty($prod->material)) {
                $material = trim($prod->material[0]);
            }
            $material = str_replace(' ', '-', $material);
        }

        if (empty($customizable_gallery) || !isset($customizable_gallery)) {
            $customizable_gallery = null;
        }

        if (empty($customizable_name) || !isset($customizable_name)) {
            $customizable_name = null;
        }

        if (empty($customizable_number) || !isset($customizable_number)) {
            $customizable_number = null;
        }

        if (empty($customizable_logo) || !isset($customizable_logo)) {
            $customizable_logo = null;
        }
        if (empty($agree_terms) || !isset($agree_terms)) {
            $agree_terms = 0;
        }

        $color = str_replace('#', '', $color);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->addnum($prod, $prod->id, $qty, $size, $color, $material, $customizable_gallery, $customizable_name, $customizable_number, $customizable_logo, $agree_terms, $size_qty, $size_price, $size_key, $color_qty, $color_price, $color_key, $material_qty, $material_price, $material_key, $keys, $values);
        $custom_item_id = $id.$size.$material.$color.$customizable_gallery.$customizable_name.$customizable_number.$customizable_logo.str_replace(str_split(' ,'), '', $values);
        $custom_item_id = str_replace(array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '', $custom_item_id);

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }

        Session::put('is_customizable', (!empty($customizable_gallery) ? true : false));

        $customProducts = env("ENABLE_CUSTOM_PRODUCT", false);
        $customProductsNumber = env("ENABLE_CUSTOM_PRODUCT_NUMBER", false);


        if ($customProducts && $customProductsNumber) {
            // Se as duas feature flags estiverem ativadas lança um erro porque não faz sentido.
            // Caso futuramente deva ser implementado algo para tratar isso, é aqui que deverá acontecer.
            // Esse erro nunca vai ser disparado, está aqui apenas para indicar ao desenvolvedor que ambas as flags estão ativas em sua aplicação.
            return redirect()->back()->with('unsuccess', __("Application error! Please contact the administrator."));
        }
        if ($customProducts && !$customProductsNumber) {
            if (!empty($cart->items[$custom_item_id]['customizable_gallery']) || !empty($cart->items[$custom_item_id]['customizable_name']) || !empty($cart->items[$custom_item_id]['customizable_logo'])) {
                if ((int)$agree_terms == 0) {
                    return redirect()->back()->with('unsuccess', __("You must signal that you agree with our terms!"));
                }
            }
            if ($customizable_gallery_count > 0) {
                if (empty($customizable_gallery)) {
                    return redirect()->back()->with('unsuccess', __("You must select a custom texture!"));
                }
            }
        }
        if ($customProductsNumber && !$customProducts) {
            if ($is_customizable_number) {
                /* if(empty($cart->items[$custom_item_id]['customizable_name']) || empty($cart->items[$custom_item_id]['customizable_number'])){
                    return redirect()->back()->with('unsuccess', __("You must fill all the custom product fields."));
                }
                if(!empty($cart->items[$custom_item_id]['customizable_name']) || !empty($cart->items[$custom_item_id]['customizable_number'])){
                    if((int)$cart->items[$custom_item_id]['customizable_number'] <= 0 || (int)$cart->items[$custom_item_id]['customizable_number'] > 99){
                        return redirect()->back()->with('unsuccess', __("Please enter a valid number!"));
                    }

                } */
            }
        }
        if ($cart->items[$custom_item_id]['dp'] == 1) {
            foreach ($cart->items as $item) {
                return redirect()->route('front.cart')->with([
                'pixel_id' => $item['item']['id'],
                'pixel_name' => $item['item']['name'],
                'pixel_category' => $item['item']['category']['name'],
                'pixel_price' => $item['price'],
                'pixel_type' => $item['item']['type'],
                'pixel_currency' => $curr['name']
            ]);
            }
        }
        if ($cart->items[$custom_item_id]['stock'] < 0) {
            foreach ($cart->items as $item) {
                return redirect()->route('front.cart')->with([
                'pixel_id' => $item['item']['id'],
                'pixel_name' => $item['item']['name'],
                'pixel_category' => $item['item']['category']['name'],
                'pixel_price' => $item['price'],
                'pixel_type' => $item['item']['type'],
                'pixel_currency' => $curr['name']
            ]);
            }
        }
        if (!empty($cart->items[$custom_item_id]['material_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['material_qty']) {
                foreach ($cart->items as $item) {
                    return redirect()->route('front.cart')->with([
                    'pixel_id' => $item['item']['id'],
                    'pixel_name' => $item['item']['name'],
                    'pixel_category' => $item['item']['category']['name'],
                    'pixel_price' => $item['price'],
                    'pixel_type' => $item['item']['type'],
                    'pixel_currency' => $curr['name']
                ]);
                }
            }
        }

        if (!empty($cart->items[$custom_item_id]['size_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['size_qty']) {
                foreach ($cart->items as $item) {
                    return redirect()->route('front.cart')->with([
                    'pixel_id' => $item['item']['id'],
                    'pixel_name' => $item['item']['name'],
                    'pixel_category' => $item['item']['category']['name'],
                    'pixel_price' => $item['price'],
                    'pixel_type' => $item['item']['type'],
                    'pixel_currency' => $curr['name']
                ]);
                }
            }
        }

        if (!empty($cart->items[$custom_item_id]['color_qty'])) {
            if ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['color_qty']) {
                foreach ($cart->items as $item) {
                    return redirect()->route('front.cart')->with([
                    'pixel_id' => $item['item']['id'],
                    'pixel_name' => $item['item']['name'],
                    'pixel_category' => $item['item']['category']['name'],
                    'pixel_price' => $item['price'],
                    'pixel_type' => $item['item']['type'],
                    'pixel_currency' => $curr['name']
                ]);
                }
            }
        }

        if (!empty($cart->items[$custom_item_id]['item']['stock']) && ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['item']['stock'])) {
            return redirect()->back()->with('unsuccess', __("Out of Stock!"));
        }

        if (!empty($cart->items[$custom_item_id]['item']['max_quantity']) && ($cart->items[$custom_item_id]['qty'] > $cart->items[$custom_item_id]['item']['max_quantity'])) {
            return redirect()->back()->with('unsuccess', __("Limit Exceeded!"));
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $item) {
            $product = Product::where('id', $item['item']->id)->first();
            if ($product->promotion_price) {
                $item['price'] = $product->promotion_price;
            }
            $cart->totalPrice += ($item['price']);
        }
        
        Session::put('cart', $cart);

        return redirect()->route('front.cart')->with([
            'pixel_id' => $item['item']['id'],
            'pixel_name' => $item['item']['name'],
            'pixel_category' => $item['item']['category']['name'],
            'pixel_price' => $item['price'],
            'pixel_type' => $item['item']['type'],
            'pixel_currency' => $curr['name']
        ]);
    }

    public function addbyone()
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $id = $_GET['id'];
        $itemid = $_GET['itemid'];
        $size_qty = $_GET['size_qty'];
        $size_price = $_GET['size_price'];
        $color_qty = $_GET['color_qty'];
        $color_price = $_GET['color_price'];
        $material_qty = $_GET['material_qty'];
        $material_price = $_GET['material_price'];
        $prod = Product::where('id', '=', $id)->where('status', '=', 1)->first(['id', 'user_id', 'slug', 'photo', 'size', 'size_qty', 'size_price', 'color', 'price', 'stock', 'type', 'file', 'link', 'license', 'license_qty', 'measure', 'whole_sell_qty', 'whole_sell_discount', 'attributes', 'max_quantity', 'weight', 'width', 'height', 'length', 'ref_code', 'ref_code_int', 'color_qty', 'color_price', 'material_qty', 'material_price', 'promotion_price']);

        if (empty($prod)) {
            $data["out_stock"] = __("Out of Stock!");
            return response()->json($data);
        }
        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->storeSettings->fixed_commission + ($prod->price/100) * $this->storeSettings->percentage_commission ;
            $prc += $prc * ($this->storeSettings->product_percent / 100);
            $prod->price = round($prc, 2);
        }

        if (!empty($prod->attributes)) {
            $attrArr = json_decode($prod->attributes, true);
            $count = count($attrArr);
            $j = 0;
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrKey => $attrVal) {
                    if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                        foreach ($attrVal['values'] as $optionKey => $optionVal) {
                            $prod->price += $attrVal['prices'][$optionKey];
                            break;
                        }
                    }
                }
            }
        }



        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        if ($cart->items[$itemid]['qty'] > 0) {
            $size_price = $cart->items[$itemid]['price'] / $cart->items[$itemid]['qty'];
            $color_price = $cart->items[$itemid]['price'] / $cart->items[$itemid]['qty'];
        } else {
            $size_price = $cart->items[$itemid]['price'];
            $color_price = $cart->items[$itemid]['price'];
        }
        $cart->adding($prod, $itemid, $size_qty, $size_price, $color_qty, $color_price, $material_qty, $material_price);
        if ($cart->items[$itemid]['stock'] < 0) {
            return 0;
        }
        if (!empty($size_qty)) {
            if ($cart->items[$itemid]['qty'] > $cart->items[$itemid]['size_qty']) {
                return 0;
            }
        }
        if (!empty($color_qty)) {
            if ($cart->items[$itemid]['qty'] > $cart->items[$itemid]['color_qty']) {
                return 0;
            }
        }
        if (!empty($material_qty)) {
            if ($cart->items[$itemid]['qty'] > $cart->items[$itemid]['material_qty']) {
                return 0;
            }
        }
        if (!empty($cart->items[$itemid]['max_quantity'])) {
            if ($cart->items[$itemid]['qty'] > $cart->items[$itemid]['max_quantity']) {
                return 0;
            }
        }

        if (!empty($cart->items[$itemid]['stock'])) {
            if ($cart->items[$itemid]['qty'] > $cart->items[$itemid]['item']['stock']) {
                return 0;
            }
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $data) {
            $cart->totalPrice += $data['price'];
        }

        Session::put('cart', $cart);
        $data[0] = $cart->totalPrice;
        $data[3] = $data[0];
        $tx = $this->storeSettings->tax;
        if ($tx != 0) {
            $tax = ($data[0] / 100) * $tx;
            $data[3] = $data[0] + $tax;
        }
        $data[4] = round($data[3], 2);
        $data[1] = $cart->items[$itemid]['qty'];
        $data[2] = $cart->items[$itemid]['price'];
        $data[0] = round($data[0] * $curr->value, 2);
        $data[2] = round($data[2] * $curr->value, 2);
        $data[3] = round($data[3] * $curr->value, 2);

        $data[0] = number_format($data[0], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        $data[2] = number_format($data[2], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        $data[3] = number_format($data[3], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        $data[4] = number_format($data[4], $first_curr->decimal_digits, $first_curr->decimal_separator, $this->storeSettings->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            $data[0] = $curr->sign.$data[0];
            $data[2] = $curr->sign.$data[2];
            $data[3] = $curr->sign.$data[3];
            $data[4] = $first_curr->sign.$data[4];
        } else {
            $data[0] = $data[0].$curr->sign;
            $data[2] = $data[2].$curr->sign;
            $data[3] = $data[3].$curr->sign;
            $data[4] = $data[4].$first_curr->sign;
        }
        return response()->json($data);
    }

    public function reducebyone()
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $id = $_GET['id'];
        $itemid = $_GET['itemid'];
        $size_qty = $_GET['size_qty'];
        $size_price = $_GET['size_price'];
        $material_qty = $_GET['material_qty'];
        $material_price = $_GET['material_price'];
        $color_qty = $_GET['color_qty'];
        $color_price = $_GET['color_price'];
        $prod = Product::where('id', '=', $id)->first(['id','user_id','slug','photo','size','size_qty','size_price','material','material_qty','material_price', 'color_qty', 'color_price', 'color','price','stock','type','file','link','license','license_qty','measure','whole_sell_qty','whole_sell_discount','attributes','max_quantity','weight','width','height','length','ref_code','ref_code_int']);
        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->storeSettings->fixed_commission + ($prod->price/100) * $this->storeSettings->percentage_commission ;
            $prc += $prc * ($this->storeSettings->product_percent / 100);
            $prod->price = round($prc, 2);
        }


        if (!empty($prod->attributes)) {
            $attrArr = json_decode($prod->attributes, true);
            $count = count($attrArr);
            $j = 0;
            if (!empty($attrArr)) {
                foreach ($attrArr as $attrKey => $attrVal) {
                    if (is_array($attrVal) && array_key_exists("details_status", $attrVal) && $attrVal['details_status'] == 1) {
                        foreach ($attrVal['values'] as $optionKey => $optionVal) {
                            $prod->price += $attrVal['prices'][$optionKey];
                            break;
                        }
                    }
                }
            }
        }


        if (!empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        if ($cart->items[$itemid]['qty'] > 0) {
            $size_price = $cart->items[$itemid]['price'] / $cart->items[$itemid]['qty'];
            $color_price = $cart->items[$itemid]['price'] / $cart->items[$itemid]['qty'];
            $material_price = $cart->items[$itemid]['price'] / $cart->items[$itemid]['qty'];
        } else {
            $size_price = $cart->items[$itemid]['price'];
            $color_price = $cart->items[$itemid]['price'];
            $material_price = $cart->items[$itemid]['price'];
        }
        $cart->reducing($prod, $itemid, $size_qty, $size_price, $color_qty, $color_price, $material_qty, $material_price);
        $cart->totalPrice = 0;
        foreach ($cart->items as $data) {
            $cart->totalPrice += $data['price'];
        }

        Session::put('cart', $cart);
        $data[0] = $cart->totalPrice;

        $data[3] = $data[0];
        $tx = $this->storeSettings->tax;
        if ($tx != 0) {
            $tax = ($data[0] / 100) * $tx;
            $data[3] = $data[0] + $tax;
        }
        $data[4] = round($data[3], 2);
        $data[1] = $cart->items[$itemid]['qty'];
        $data[2] = $cart->items[$itemid]['price'];
        $data[0] = round($data[0] * $curr->value, 2);
        $data[2] = round($data[2] * $curr->value, 2);
        $data[3] = round($data[3] * $curr->value, 2);

        $data[0] = number_format($data[0], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        $data[2] = number_format($data[2], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        $data[3] = number_format($data[3], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
        $data[4] = number_format($data[4], $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator);
        if ($this->storeSettings->currency_format == 0) {
            $data[0] = $curr->sign.$data[0];
            $data[2] = $curr->sign.$data[2];
            $data[3] = $curr->sign.$data[3];
            $data[4] = $first_curr->sign.$data[4];
        } else {
            $data[0] = $data[0].$curr->sign;
            $data[2] = $data[2].$curr->sign;
            $data[3] = $data[3].$curr->sign;
            $data[4] = $data[4].$first_curr->sign;
        }
        return response()->json($data);
    }

    public function upcolor()
    {
        $id = $_GET['id'];
        $color = $_GET['color'];
        $prod = Product::where('id', '=', $id)->first(['id','user_id','slug','photo','size','size_qty','size_price','color','price','stock','type','file','link','license','license_qty','measure','whole_sell_qty','whole_sell_discount','attributes','max_quantity','weight','width','height','length','ref_code','ref_code_int']);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->updateColor($prod, $id, $color);
        Session::put('cart', $cart);
        $data["success"] = __("Successfully Changed The Color");

        return response()->json($data);
    }


    public function removecart($id)
    {
        Session::forget('already');
        Session::forget('coupon');
        Session::forget('coupon_code');
        Session::forget('coupon_total');
        Session::forget('coupon_total1');
        Session::forget('coupon_percentage');

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::find($this->storeSettings->currency_id);
        }
        $first_curr = Currency::where('id', '=', 1)->first();
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
            $data[0] = $cart->totalPrice;
            $data[3] = $data[0];
            $tx = $this->storeSettings->tax;
            if ($tx != 0) {
                $tax = ($data[0] / 100) * $tx;
                $data[3] = $data[0] + $tax;
            }
            $data[4] = round($data[3], 2);

            $data[0] = number_format($data[0], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
            $data[3] = number_format($data[3], $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator);
            $data[4] = number_format($data[4], $first_curr->decimal_digits, $first_curr->decimal_separator, $first_curr->thousands_separator);

            if ($this->storeSettings->currency_format == 0) {
                $data[0] = $curr->sign.$data[0];
                $data[3] = $curr->sign.$data[3];
                $data[4] = $first_curr->sign.$data[4];
            } else {
                $data[0] = $data[0].$curr->sign;
                $data[3] = $data[3].$curr->sign;
                $data[4] = $data[4].$first_curr->sign;
            }

            $data[1] = count($cart->items);
            return response()->json($data);
        } else {
            Session::forget('cart');

            $data = 0;
            return response()->json($data);
        }
    }

    public function coupon()
    {
        $code = $_GET['code'];
        if (!Session::has('cart')) {
            return view('front.cart');
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart->totalPrice;
        $tx = $this->storeSettings->tax;
        if ($tx != 0) {
            $tax = ($total / 100) * $tx;
            $total = $total + $tax;
        }

        $fnd = Coupon::where('code', '=', $code)->get()->count();
        $couponError = false;

        if ($fnd < 1) {
            $data["not_found"] = __("No Coupon Found");
            $couponError = true;
        } else {
            $coupon = Coupon::where('code', '=', $code)->first();
            if ($coupon->category_id) {
                $couponCategories = [];
                $idProducts = [];
                $idCategories = [];
                foreach ($cart->items as $item) {
                    $idProducts[] = $item['item']['id'];
                }
                if ($idProducts) {
                    $products = Product::whereIn('id', $idProducts)->with('category')->get();
                    $idCategories = $products->pluck('category.id')->toArray();
                    $couponCategories[] = $coupon->category_id;
                    if (array_diff($idCategories, $couponCategories) != null) {
                        $data["not_found"] = __("This coupon is not available for this product");
                    }
                }
            }
            if ($coupon->brand_id) {
                $couponBrands = [];
                $idProducts = [];
                $idBrands = [];
                foreach ($cart->items as $item) {
                    $idProducts[] = $item['item']['id'];
                }
                if ($idProducts) {
                    $products = Product::whereIn('id', $idProducts)->with('brand')->get();
                    $idBrands = $products->pluck('brand.id')->toArray();
                    $couponBrands[] = $coupon->brand_id;
                    if (array_diff($idBrands, $couponBrands) != null) {
                        $data["not_found"] = __("This coupon is not available for this product");
                    }
                }
            }      
            if (Session::has('currency')) {
                $curr = Currency::find(Session::get('currency'));
            } else {
                $curr = Currency::find($this->storeSettings->currency_id);
            }

            $first_curr = Currency::where('id', '=', 1)->first();

            if (!is_null($coupon->times) && ($coupon->used >= $coupon->times)) {
                $data["not_found"] = __("This coupon is not available anymore");
                $couponError = true;
            }

            if (!$couponError) {
                $today = date('Y-m-d');
                $from = date('Y-m-d', strtotime($coupon->start_date));
                $to = date('Y-m-d', strtotime($coupon->end_date));

                if ($from <= $today && $to >= $today) {
                    if ($coupon->status == 1) {
                        if (!is_null($coupon->minimum_value)) {
                            if ($total < $coupon->minimum_value) {
                                $data["not_found"] = __("The total value is less than the minimum possible value.");
                                $couponError = true;
                            }
                        }
                        if (!is_null($coupon->maximum_value)) {
                            if ($total > $coupon->maximum_value) {
                                $data["not_found"] = __("The total value is greater than the maximum possible value");
                                $couponError = true;
                            }
                        }


                        $val = Session::has('already') ? Session::get('already') : null;
                        if ($val == $code) {
                            $data["already"] = __("Coupon Already Applied");
                            $couponError = true;
                        }

                        if (!$couponError) {
                            if ($coupon->type == 0) {
                                Session::put('already', $code);
                                $coupon->price = (int) $coupon->price;
                                $val = $total / 100;
                                $sub = $val * $coupon->price;
                                $total = $total - $sub;
                                $data[6] = round($total, 2);
                                $data[0] = round($total * $curr->value, 2);
                                Session::put('coupon_total', $data[0]);

                                $data[0] = number_format($data[0], 2, $this->storeSettings->decimal_separator, $this->storeSettings->thousands_separator);
                                $data[6] = number_format($data[6], 2, $this->storeSettings->decimal_separator, $this->storeSettings->thousands_separator);

                                if ($this->storeSettings->currency_format == 0) {
                                    $data[0] = $curr->sign . $data[0];
                                    $data[6] = $first_curr->sign . $data[6];
                                } else {
                                    $data[0] = $data[0] . $curr->sign;
                                    $data[6] = $data[6] . $first_curr->sign;
                                }
                                $data[1] = $code;
                                $data[2] = round($sub * $curr->value, 2);
                                Session::put('coupon', $data[2]);
                                Session::put('coupon_code', $code);
                                Session::put('coupon_id', $coupon->id);
                                $data[3] = $coupon->id;
                                $data[4] = $coupon->price . "%";
                                $data[5] = 1;

                                Session::put('coupon_percentage', $data[4]);
                            } else {
                                Session::put('already', $code);
                                $total = $total - $coupon->price;
                                $data[6] = round($total, 2);
                                $data[0] = round($total * $curr->value, 2);
                                $data[1] = $code;
                                $data[2] = round($coupon->price * $curr->value, 2);
                                Session::put('coupon', $data[2]);
                                Session::put('coupon_code', $code);
                                Session::put('coupon_id', $coupon->id);
                                Session::put('coupon_total', $data[0]);
                                $data[3] = $coupon->id;

                                $data[0] = number_format($data[0], 2, $this->storeSettings->decimal_separator, $this->storeSettings->thousands_separator);
                                $data[4] = number_format($data[2], 2, $this->storeSettings->decimal_separator, $this->storeSettings->thousands_separator);
                                $data[6] = number_format($data[6], 2, $this->storeSettings->decimal_separator, $this->storeSettings->thousands_separator);

                                if ($this->storeSettings->currency_format == 0) {
                                    $data[4] = $curr->sign . $data[4];
                                    $data[0] = $curr->sign . $data[0];
                                    $data[6] = $first_curr->sign . $data[6];
                                } else {
                                    $data[4] = $data[2] . $curr->sign;
                                    $data[0] = $data[0] . $curr->sign;
                                    $data[6] = $data[6] . $first_curr->sign;
                                }


                                Session::put('coupon_percentage', 0);

                                $data[5] = 1;
                            }
                            $data["success"] = __("Coupon Found");
                        }
                    } else {
                        $data["not_found"] = __("No Coupon Found");
                    }
                } else {
                    $data["not_found"] = __("No Coupon Found");
                }
            }
        }
        return response()->json($data);
    }

    public function couponcheck(Request $request)
    {
        $code = $request->code;
        $coupon = Coupon::where('code', $code)->first();
        /* Validation */

        // There is no Coupon
        if (!$coupon) {
            $data["not_found"] = __("This coupon doesn't exist.");
            return response()->json($data);
        }
        // Coupon is disabled
        if (!$coupon->status) {
            $data["not_found"] = __("No coupon found");
            return response()->json($data);
        }
        // Coupon has no quantity anymore
        if (!is_null($coupon->times) && ($coupon->used >= $coupon->times)) {
            $data["not_found"] = __("This coupon is not available anymore");
            return response()->json($data);
        }

        //Total price of the item(s) in the cart

        $cart = new Cart(Session::get('cart'));
        if ($coupon->category_id) {
            $couponCategories = [];
            $idProducts = [];
            $idCategories = [];
            foreach ($cart->items as $item) {
                $idProducts[] = $item['item']['id'];
            }
            if ($idProducts) {
                $products = Product::whereIn('id', $idProducts)->with('category')->get();
                $idCategories = $products->pluck('category.id')->toArray();
                $couponCategories[] = $coupon->category_id;
                if (array_diff($idCategories, $couponCategories) != null) {
                    $data["not_found"] = __("This coupon is not available for this product");
                }
            }
        }
        if ($coupon->brand_id) {
            $couponBrands = [];
            $idProducts = [];
            $idBrands = [];
            foreach ($cart->items as $item) {
                $idProducts[] = $item['item']['id'];
            }
            if ($idProducts) {
                $products = Product::whereIn('id', $idProducts)->with('brand')->get();
                $idBrands = $products->pluck('brand.id')->toArray();
                $couponBrands[] = $coupon->brand_id;
                if (array_diff($idBrands, $couponBrands) != null) {
                    $data["not_found"] = __("This coupon is not available for this product");
                }
            }
        }      
        $cart_total_price = $cart->totalPrice;
        $tax = $this->storeSettings->tax;
        if ($tax > 0) {
            $tax = ($cart_total_price / 100) * $tax;
            $cart_total_price += $tax;
        }

        // // Coupon has expired
        $today = date('Y-m-d');
        $from = date('Y-m-d', strtotime($coupon->start_date));
        $to = date('Y-m-d', strtotime($coupon->end_date));

        //Coupon isn't in the delimited period of use
        if ($from > $today || $today > $to) {
            $data["not_found"] = __("This coupon is not available anymore");
            return response()->json($data);
        }

        //Coupon is or less than the minimum value or greater than the maximum value of use.
        if (!is_null($coupon->minimum_value)) {
            if ($cart_total_price < $coupon->minimum_value) {
                $data["not_found"] = __("The total value is less than the minimum possible value.");
                return response()->json($data);
            }
        }

        if (!is_null($coupon->maximum_value)) {
            if ($cart_total_price > $coupon->maximum_value) {
                $data["not_found"] = __("The total value is greater than the maximum possible value");
                return response()->json($data);
            }
        }

        // Coupon already applied
        $val = Session::has('already') ? Session::get('already') : null;
        if (!strcmp($val, $code)) {
            $data["already"] = __("Coupon already applied");
            return response()->json($data);
        }

        $curr = Currency::find($this->storeSettings->currency_id);

        // 0 = Porcentagem
        // 1 = Montante
        if ($coupon->type == 0) {
            $coupon->price = (int) $coupon->price;
            $coupon_percentage = $cart_total_price / 100;
            $subtract = $coupon_percentage * $coupon->price;
            $total = $cart_total_price - $subtract;

            $data[2] = round($subtract, 2);
            $data[4] = $coupon->price . "%";
        }

        if ($coupon->type == 1) {
            $total = $cart_total_price - $coupon->price;

            $data[2] = round($coupon->price * $curr->value, 2);
            $data[4] = 0;
        }

        if ($total <= 0) {
            $data["not_found"] = __("This coupon cannot be applied on this buy");
            return response()->json($data);
        }

        Session::put('already', $code);

        $data[0] = round($total * $curr->value, 2);
        $data[1] = $code;
        $data[3] = $coupon->id;
        $data[5] = 1;
        $data[6] = round($total, 2);

        Session::put('coupon_total1', $data[0]);
        Session::put('coupon', $data[2]);
        Session::put('coupon_code', $code);
        Session::put('coupon_id', $coupon->id);
        Session::forget('coupon_total');

        Session::put('coupon_percentage', $data[4]);

        $data["success"] = __("Coupon Found");
        return response()->json($data);
    }
}
