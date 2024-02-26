<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;

use Illuminate\Database\Eloquent\Model;

class Cart extends CachedModel
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    protected $storeSettings;
    protected $storeLocale;

    public function toJson($options = 0)
    {
        $cart = [];
        $cart['items'] = $this->items;
        $cart['totalQty'] = $this->totalQty;
        $cart['totalPrice'] = $this->totalPrice;

        return json_encode($cart, $options);
    }

    public function __construct($oldCart = null)
    {
        parent::__construct();

        $this->storeSettings = resolve('storeSettings');

        $this->storeLocale = (Session::has('language') ? Language::find(Session::get('language')) : Language::find($this->storeSettings->lang_id));

        if ($oldCart && is_object($oldCart)) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }

        if ($oldCart && is_array($oldCart)) {
            $this->items = $oldCart['items'];
            $this->totalQty = $oldCart['totalQty'];
            $this->totalPrice = $oldCart['totalPrice'];
        }
    }

    // **************** ADD TO CART *******************

    public function add($item, $id, $material, $size, $color, $customizable_gallery, $customizable_name, $customizable_number, $customizable_logo, $agree_terms, $keys, $values)
    {
        $size_cost = 0;
        $color_cost = 0;
        $material_cost = 0;
        $storedItem = ['qty' => 0,'size_key' => 0, 'photo' => $item->photo, 'size_qty' =>  $item->size_qty, 'color_key' => 0, 'color_qty' => $item->color_qty, 'color_price' => $item->color_price,'material_qty' => $item->material_qty,'material' => $item->material, 'material_price' => $item->material_price, 'max_quantity' => $item->max_quantity,'size_price' => $item->size_price, 'size' => $item->size, 'color' => $item->color, 'customizable_gallery' => $item->customizable_gallery, 'customizable_name' => $item->customizable_name, 'customizable_number' => $item->customizable_number, 'customizable_logo' => $item->customizable_logo, 'agree_terms' => $item->agree_terms, 'stock' => $item->stock, 'price' => $item->price, 'item' => $item, 'license' => '', 'dp' => '0','keys' => $keys, 'values' => $values];
        $custom_item_id = $id.$size.$material.$color.$customizable_gallery.$customizable_name.$customizable_number.$customizable_logo.str_replace(str_split(' ,'), '', $values);

        $custom_item_id = str_replace(array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '', $custom_item_id);
        if ($item->type == 'Physical') {
            if ($this->items) {
                if (array_key_exists($custom_item_id, $this->items)) {
                    $storedItem = $this->items[$custom_item_id];
                }
            }
        } else {
            if ($this->items) {
                if (array_key_exists($custom_item_id, $this->items)) {
                    $storedItem = $this->items[$custom_item_id];
                    $storedItem['dp'] = 1;
                }
            }
        }
        $storedItem['qty']++;
        $stck = (string)$item->stock;
        if ($stck != null) {
            $storedItem['stock']--;
        }
        if (!empty($item->size)) {
            $storedItem['size'] = $item->size[0];
        }
        if (!empty($size)) {
            $storedItem['size'] = $size;
        }
        if (!empty($photo)) {
            $storedItem['photo'] = $photo;
        }
        if (!empty($customizable_gallery)) {
            $storedItem['customizable_gallery'] = $customizable_gallery;
        }
        if (!empty($customizable_name)) {
            $storedItem['customizable_name'] = $customizable_name;
        }
        if (!empty($customizable_number)) {
            $storedItem['customizable_number'] = $customizable_number;
        }
        if (!empty($customizable_logo)) {
            $storedItem['customizable_logo'] = $customizable_logo;
        }
        if (!empty($agree_terms)) {
            $storedItem['agree_terms'] = $item->agree_terms[0];
        }

        if (!empty($item->size_qty)) {
            $storedItem['size_qty'] = $item->size_qty[0];
        }
        if ($item->size_price != null) {
            $storedItem['size_price'] = $item->size_price[0];
            $size_cost = $item->size_price[0];
            $size_cost += $size_cost * ($this->storeSettings->product_percent / 100);
        }
        if (!empty($item->color)) {
            $storedItem['color'] = $item->color[0];
        }
        if (!empty($color)) {
            $storedItem['color'] = $color;
        }
        if (!empty($item->color_qty)) {
            $storedItem['color_qty'] = $item->color_qty[0];
        }
        if ($item->color_price != null) {
            $storedItem['color_price'] = $item->color_price[0];
            $color_cost = $item->color_price[0];
            $color_cost += $color_cost * ($this->storeSettings->product_percent / 100);
        }

        if (!empty($material)) {
            $storedItem['material'] = $material;
        }

        if (!empty($item->material_qty)) {
            foreach ($item->material as $key => $material_qty) {
                if ($item->material_qty[$key] > 0) {
                    $storedItem['material_qty'] = $item->material_qty[$key];
                    break;
                }
            }
        }

        if ($item->material_price != null) {
            foreach ($item->material as $key => $material_price) {
                if ($item->material_qty[$key] > 0) {
                    $storedItem['material_price'] = $item->material_price[$key];
                    $material_cost = $item->material_price[$key];
                    $material_cost += $material_cost * ($this->storeSettings->product_percent / 100);
                    break;
                }
            }
        }

        if (!empty($keys)) {
            $storedItem['keys'] = $keys;
        }

        if (!empty($max_quantity)) {
            $storedItem['max_quantity'] = $max_quantity;
        }

        if (!empty($values)) {
            $storedItem['values'] = $values;
        }
        $item->price += $size_cost;
        if ($storedItem['item']->attributes['promotion_price'] > 0) {
            $price_aux = $storedItem['item']->attributes['promotion_price'];
        } else {
            $price_aux = $storedItem['item']->attributes['price'];
        }

        if (!empty($item->whole_sell_qty)) {
            $ultimo = 0;
            foreach (array_combine($item->whole_sell_qty, $item->whole_sell_discount) as $whole_sell_qty => $whole_sell_discount) {
                if ($storedItem['qty'] >= $whole_sell_qty && $whole_sell_qty > $ultimo) {
                    $ultimo = $whole_sell_qty;
                    $whole_discount[$custom_item_id] = $whole_sell_discount;
                    Session::put('current_discount', $whole_discount);
                    break;
                }
            }
            if (Session::has('current_discount')) {
                $data = Session::get('current_discount');
                if (array_key_exists($custom_item_id, $data)) {
                    $temp_price = $storedItem['item']->attributes['price'];
                    $discount = $temp_price * ($data[$custom_item_id] / 100);
                    $price_aux = $temp_price - $discount;
                }
            }
        }

        $storedItem['price'] = $price_aux * $storedItem['qty'];
        $this->items[$custom_item_id] = $storedItem;
        $this->totalQty++;
    }

    // **************** ADD TO CART ENDS *******************



    // **************** ADD TO CART MULTIPLE *******************

    public function addnum($item, $id, $qty, $size, $color, $material, $customizable_gallery, $customizable_name, $customizable_number, $customizable_logo, $agree_terms, $size_qty, $size_price, $size_key, $color_qty, $color_price, $color_key, $material_qty, $material_price, $material_key, $keys, $values)
    {
        $size_cost = 0;
        $color_cost = 0;
        $material_cost = 0;
        $storedItem = ['qty' => 0,'size_key' => 0, 'size_qty' =>  $item->size_qty,'size_price' => $item->size_price, 'color_key' => 0, 'color_qty' => $item->color_qty, 'color_price' => $item->color_price,'material_key' => 0, 'material_qty' => $item->material_qty, 'material_price' => $item->material_price, 'max_quantity' => $item->max_quantity,'size' => $item->size, 'color' => $item->color,'material' => $item->material, 'customizable_gallery' => $item->customizable_gallery,'customizable_name' => $item->customizable_name, 'customizable_number' => $item->customizable_number, 'customizable_logo' => $item->customizable_logo, 'agree_terms' => $item->agree_terms, 'stock' => $item->stock, 'price' => $item->price, 'item' => $item, 'license' => '', 'dp' => '0','keys' => $keys, 'values' => $values];
        $custom_item_id = $id.$size.$color.$material.$customizable_gallery.$customizable_name.$customizable_number.$customizable_logo.str_replace(str_split(' ,'), '', $values);
        $custom_item_id = str_replace(array( '\'', '"', ',', '.', ' ', ';', '<', '>' ), '', $custom_item_id);
        if ($item->type == 'Physical') {
            if ($this->items) {
                if (array_key_exists($custom_item_id, $this->items)) {
                    $storedItem = $this->items[$custom_item_id];
                }
            }
        } else {
            if ($this->items) {
                if (array_key_exists($custom_item_id, $this->items)) {
                    $storedItem = $this->items[$custom_item_id];
                    $storedItem['dp'] = 1;
                }
            }
        }

        if (config("features.marketplace")) {
            $qty = 1;
        }

        $storedItem['qty'] = $storedItem['qty'] + $qty;
        $stck = (string)$item->stock;
        if ($stck != null) {
            $storedItem['stock']--;
        }
        if (!empty($item->size)) {
            $storedItem['size'] = $item->size[0];
        }

        if (!empty($max_quantity)) {
            $storedItem['max_quantity'] = $max_quantity;
        }
        if (!empty($material)) {
            $storedItem['material'] = $material;
        }

        if (!empty($size)) {
            $storedItem['size'] = $size;
        }
        if (!empty($size_key)) {
            $storedItem['size_key'] = $size_key;
        }
        if (!empty($item->size_qty)) {
            $storedItem['size_qty'] = $item->size_qty [$size_key];
        }
        if (!empty($size_qty)) {
            $storedItem['size_qty'] = $size_qty;
        }
        if (!empty($item->size_price)) {
            $storedItem['size_price'] = $item->size_price[$size_key];
            $size_cost = $item->size_price[$size_key];
            $size_cost += $size_cost * ($this->storeSettings->product_percent / 100);
        }
        if (!empty($size_price)) {
            $storedItem['size_price'] = $size_price;
            $size_cost = $size_price;
        }

        if (!empty($color_key)) {
            $storedItem['color_key'] = $color_key;
        }
        if (!empty($item->color)) {
            $storedItem['color'] = $item->color[$color_key];
        }
        if (!empty($color)) {
            $storedItem['color'] = $color;
        }
        if (!empty($item->color_qty)) {
            $storedItem['color_qty'] = $item->color_qty [$color_key];
        }
        if (!empty($color_qty)) {
            $storedItem['color_qty'] = $color_qty;
        }
        if (!empty($item->color_price)) {
            $storedItem['color_price'] = $item->color_price[$color_key];
            $color_cost = $item->color_price[$color_key];
            $color_cost += $color_cost * ($this->storeSettings->product_percent / 100);
        }
        if (!empty($color_price)) {
            $storedItem['color_price'] = $color_price;
            $color_cost = $color_price;
        }

        if (!empty($item->material)) {
            $storedItem['material'] = $item->material[0];
        }
        if (!empty($material)) {
            $storedItem['material'] = $material;
        }
        if (!empty($material_key)) {
            $storedItem['material_key'] = $material_key;
        }
        if (!empty($item->material_qty)) {
            $storedItem['material_qty'] = $item->material_qty [0];
        }
        if (!empty($material_qty)) {
            $storedItem['material_qty'] = $material_qty;
        }
        if (!empty($item->material_price)) {
            $storedItem['material_price'] = $item->material_price[0];
            $material_cost = $item->material_price[0];
            $material_cost += $material_cost * ($this->storeSettings->product_percent / 100);
        }
        if (!empty($material_price)) {
            $storedItem['material_price'] = $material_price;
            $material_cost = $material_price;
        }


        if (!empty($customizable_gallery)) {
            $storedItem['customizable_gallery'] = $customizable_gallery;
        }
        if (!empty($customizable_name)) {
            $storedItem['customizable_name'] = $customizable_name;
        }
        if (!empty($customizable_number)) {
            $storedItem['customizable_number'] = $customizable_number;
        }
        if (!empty($customizable_logo)) {
            $storedItem['customizable_logo'] = $customizable_logo;
        }
        if (!empty($agree_terms)) {
            $storedItem['agree_terms'] = $agree_terms;
        }

        if (!empty($item->agree_terms)) {
            $storedItem['agree_terms'] = $item->agree_terms[0];
        }

        if (!empty($keys)) {
            $storedItem['keys'] = $keys;
        }
        if (!empty($values)) {
            $storedItem['values'] = $values;
        }
        if ($size_cost > 0) {
            $item->price += $size_cost;
        } elseif ($color_cost >0) {
            $item->price += $color_cost;
        } elseif ($material_cost > 0) {
            $item->price += $material_cost;
        }

        if (!$storedItem['item']->attributes['promotion_price']) {
            $price_aux = $storedItem['item']->attributes['price'];
        } else {
            $price_aux = $storedItem['item']->attributes['promotion_price'];
        }
        
        if (!empty($item->whole_sell_qty)) {
            $ultimo = 0;
            foreach (array_combine($item->whole_sell_qty, $item->whole_sell_discount) as $whole_sell_qty => $whole_sell_discount) {
                if ($storedItem['qty'] >= $whole_sell_qty && $whole_sell_qty > $ultimo) {
                    $ultimo = $whole_sell_qty;
                    $whole_discount[$custom_item_id] = $whole_sell_discount;
                    Session::put('current_discount', $whole_discount);
                }
            }

            if (Session::has('current_discount')) {
                $data = Session::get('current_discount');
                if (array_key_exists($custom_item_id, $data)) {
                    $temp_price = $storedItem['item']->attributes['price'];
                    $discount = $temp_price * ($data[$custom_item_id] / 100);
                    $price_aux = $temp_price - $discount;
                }
            }
        }

        $storedItem['price'] = $price_aux * $storedItem['qty'];
        $this->items[$custom_item_id] = $storedItem;
        $this->totalQty++;
    }


    // **************** ADD TO CART MULTIPLE ENDS *******************


    // **************** ADDING QUANTITY *******************

    public function adding($item, $id, $size_qty, $size_price, $color_qty, $color_price, $material_qty, $material_price)
    {
        $storedItem = ['qty' => 0,'size_key' => 0, 'size_qty' =>  $item->size_qty, 'color_qty' => $item->color_qty, 'max_quantity' =>  $item->max_quantity, 'size_price' => $item->size_price, 'color_price' => $item->color_price, 'size' => $item->size, 'color' => $item->color, 'stock' => $item->stock, 'price' => $item->price, 'item' => $item, 'license' => '', 'dp' => '0','keys' => '', 'values' => ''];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id];
            }
        }
        $storedItem['qty']++;

        if ($item->stock != null) {
            $storedItem['stock']--;
        }
        $item->price = (double)$size_price;
        if ($storedItem['item']->attributes['promotion_price'] > 0) {
            $price_aux = $storedItem['item']->attributes['promotion_price'];
        } else {
            $price_aux = $storedItem['item']->attributes['price'];
        }
        if (!empty($item->whole_sell_qty)) {
            $ultimo = 0;
            foreach (array_combine($item->whole_sell_qty, $item->whole_sell_discount) as $whole_sell_qty => $whole_sell_discount) {
                if ($storedItem['qty'] >= $whole_sell_qty && $whole_sell_qty > $ultimo) {
                    $ultimo = $whole_sell_qty;
                    $whole_discount[$id] = $whole_sell_discount;
                    Session::put('current_discount', $whole_discount);
                    break;
                }
            }

            if (Session::has('current_discount')) {
                $data = Session::get('current_discount');
                if (array_key_exists($id, $data)) {
                    $temp_price = $storedItem['item']->attributes['price'];
                    $discount = $temp_price * ($data[$id] / 100);
                    $price_aux = $temp_price - $discount;
                }
            }
        }
        $storedItem['price'] = $price_aux * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty++;
    }

    // **************** ADDING QUANTITY ENDS *******************


    // **************** REDUCING QUANTITY *******************

    public function reducing($item, $id, $size_qty, $size_price, $color_qty, $color_price, $material_qty, $material_price)
    {
        $storedItem = ['qty' => 0,'size_key' => 0, 'color_qty' => $item->color_qty, 'color_price' => $item->color_price,'material_qty' => $item->material_qty, 'material_price' => $item->material_price, 'size_qty' =>  $item->size_qty, 'size_price' => $item->size_price, 'max_quantity' =>  $item->max_quantity,'size' => $item->size, 'color' => $item->color,'material' => $item->material, 'stock' => $item->stock, 'price' => $item->price, 'item' => $item, 'license' => '', 'dp' => '0','keys' => '', 'values' => ''];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storedItem = $this->items[$id];
            }
        }
        $storedItem['qty']--;
        if ($item->stock != null) {
            $storedItem['stock']++;
        }

        $item->price = (double)$size_price;
        if ($storedItem['item']->attributes['promotion_price'] > 0) {
            $price_aux = $storedItem['item']->attributes['promotion_price'];
        } else {
            $price_aux = $storedItem['item']->attributes['price'];
        }
        if (!empty($item->whole_sell_qty)) {
            $len = count($item->whole_sell_qty);
            foreach ($item->whole_sell_qty as $key => $data1) {
                if ($storedItem['qty'] < $item->whole_sell_qty[$key]) {
                    if ($storedItem['qty'] < $item->whole_sell_qty[0]) {
                        Session::forget('current_discount');
                    }
                }
            }
            if (Session::has('current_discount')) {
                $data = Session::get('current_discount');
                if (array_key_exists($id, $data)) {
                    $temp_price = $this->items[$id]['item']->attributes['price'];
                    $discount = $temp_price * ($data[$id] / 100);
                    $price_aux = $temp_price - $discount;
                }
            }
        }

        $storedItem['price'] = $price_aux * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty--;
    }

    // **************** REDUCING QUANTITY ENDS *******************

    public function updateLicense($id, $license)
    {
        $this->items[$id]['license'] = $license;
    }

    public function updateColor($item, $id, $color)
    {
        $this->items[$id]['color'] = $color;
    }

    public function removeItem($id)
    {
        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
        if (Session::has('current_discount')) {
            $data = Session::get('current_discount');
            if (array_key_exists($id, $data)) {
                unset($data[$id]);
                Session::put('current_discount', $data);
            }
        }
    }

    /**
     * @return array object
     */
    public function getItemsWithMeasures()
    {
        $items = [];
        foreach ($this->items as $prod) {
            $item_weight = isset($prod['item']->original['weight']) ? $prod['item']->original['weight'] : 0;
            $item_width = isset($prod['item']->original['width']) ? $prod['item']->original['width'] : 0;
            $item_height = isset($prod['item']->original['height']) ? $prod['item']->original['height'] : 0;
            $item_length = isset($prod['item']->original['length']) ? $prod['item']->original['length'] : 0;
            $temp_weight = $this->checkMeasure($item_weight, 'weight');
            $temp_width = $this->checkMeasure($item_width, 'width');
            $temp_height = $this->checkMeasure($item_height, 'height');
            $temp_length = $this->checkMeasure($item_length, 'length');

            $new_item = (object)[
                "quantity" => $prod['qty'],
                "height" => $temp_height,
                "width"  => $temp_width,
                "length"  => $temp_length,
                "weight"  => $temp_weight
            ];
            $items[] = $new_item;
        }
        return $items;
    }

    //--- Calculate cart weight and measurements
    public function getWeight()
    {
        $cart_weight = 0;
        foreach ($this->items as $prod) {
            $item_weight = isset($prod['item']->original['weight']) ? $prod['item']->original['weight'] : 0;
            $cart_weight = $cart_weight + ($this->checkMeasure($item_weight, 'weight') * $prod['qty']);
        }
        return $cart_weight;
    }
    public function getWidth()
    {
        $width = $this->checkMeasure($this->getMeasure(), 'width');
        return $width;
    }
    public function getHeight()
    {
        $height = $this->checkMeasure($this->getMeasure(), 'height');
        return $height;
    }
    public function getLenght()
    {
        $length = $this->checkMeasure($this->getMeasure(), 'length');
        return $length;
    }
    private function checkMeasure($value, $measure)
    {
        switch ($measure) {
            case 'weight':
                $default = empty($this->storeSettings->correios_weight)? 0.05 : $this->storeSettings->correios_weight;
                $default < 0.05 ? $default = 0.05 : '';
                break;
            case 'width':
                $default = empty($this->storeSettings->correios_width)? 10 : $this->storeSettings->correios_width;
                $default < 10 ? $default = 10 : '';
                break;
            case 'height':
                $default = empty($this->storeSettings->correios_height)? 1 : $this->storeSettings->correios_height;
                $default < 1 ? $default = 1 : '';
                break;
            case 'length':
                $default = empty($this->storeSettings->correios_length)? 15 : $this->storeSettings->correios_length;
                $default < 15 ? $default = 15 : '';
                break;
        }
        if (empty($value) || $value < $default) {
            return $default;
        }
        return $value;
    }
    public function getVolume()
    {
        $cart_volume = 0;
        foreach ($this->items as $prod) {
            $item_width = isset($prod['item']->original['width']) ? $prod['item']->original['width'] : 0;
            $item_height = isset($prod['item']->original['height']) ? $prod['item']->original['height'] : 0;
            $item_length = isset($prod['item']->original['length']) ? $prod['item']->original['length'] : 0;
            $temp_width = $this->checkMeasure($item_width, 'width');
            $temp_height = $this->checkMeasure($item_height, 'height');
            $temp_length = $this->checkMeasure($item_length, 'length');
            $cart_volume = $cart_volume + (($temp_width * $temp_height * $temp_length) * $prod['qty']);
        }
        return $cart_volume;
    }
    private function getMeasure()
    {
        $volume = $this->getVolume();
        if (!empty($volume)) {
            $raiz = pow($volume, (1 / 3));
            $measure = ceil($raiz);
            return $measure;
        } else {
            return 0;
        }
    }
}
