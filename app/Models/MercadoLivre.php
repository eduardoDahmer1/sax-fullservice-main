<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class MercadoLivre extends CachedModel
{
    protected $table = 'mercadolivre';

    private $gallery_array = [];
    private $shipping_array = [];

    /* Data which will receive mass assignment. */
    protected $fillable = [
        'app_id',
        'client_secret',
        'access_token',
        'refresh_token'
    ];

    public $timestamps = true;

    /* General curlPost function to send API requests to Meli. */
    public function curlPost($url, $headers = [], $data = []) : array
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        $resp = curl_exec($curl);
        $resp = (array) json_decode($resp);
        return $resp;
    }

    /* General curlPost function to send API requests to Meli. */
    public function curlPut($url, $headers = [], $data = []) : array
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $resp = curl_exec($curl);
        $resp = (array) json_decode($resp);
        return $resp;
    }

    /* General curlGet function to send API requests to Meli. */
    public function curlGet($url, $headers = []) : array
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $resp = curl_exec($curl);
        $resp = (array) json_decode($resp);
        return $resp;
    }

    /* Sync store Product format with Meli Product format and returns it. */
    public function productBusinessLogic(Product $product, $category_brand)
    {
        // Condition 2 = new / 1 = used
        $condition = "new";
        if($product->product_condition != 0) {
            if($product->product_condition == 1) {
                $condition = "used";
            }
        }
        // TODO: tratar +60 chars
        $name = $product->mercadolivre_name . " - No Ofertar";
        if(env("APP_ENV") == "production") {
            $name = $product->mercadolivre_name;
        }

        $attributes = $this->getProductAttributes($product) ?? null;

        if(!$attributes) {
            return redirect()->route('admin-prod-index')->with('error', __("Nenhum atributo foi cadastrado. Acesse Ações -> Editar -> Mercado Livre e cadastre os Atributos para melhorar seu anúncio."));
        }

        $categoryId = $category_brand['category_id'];

        $businessLogic = [
            "title" => $name,
            "category_id" => $categoryId,
            "price" => $product->mercadolivre_price ?? $product->price,
            "currency_id" => "BRL",
            "available_quantity" => $product->stock,
            "buying_mode" => "buy_it_now",
            "condition" => $condition,
            "listing_type_id" => $product->mercadolivre_listing_type_id,
            "sale_terms" => [
                [
                    "id" => "WARRANTY_TYPE",
                    "name" => "Tipo de Garantia",
                    "value_id" => $product->mercadolivre_warranty_type_id,
                    "value_name" => $product->mercadolivre_warranty_type_name,
                    "value_struct" => null,
                    "values" => [
                        [
                            "id" => $product->mercadolivre_warranty_type_id,
                            "name" => $product->mercadolivre_warranty_type_name,
                            "struct" => null,
                        ],
                    ],
                ], [
                    "id" => "WARRANTY_TIME",
                    "value_name" => $product->mercadolivre_warranty_time . ' ' . $product->mercadolivre_warranty_time_unit
                ],
            ],
            "pictures" => $this->getPicturesArray($product),
            "shipping" => [
                "mode" => "custom",
                "local_pick_up" => Pickup::count() > 0 ? true : false,
                "free_shipping" => false, //TODO: Option to change THIS.
                "costs" => $this->getPaidShippings(),
            ],
            "attributes" => $attributes,
        ];

        if($product->mercadolivre_without_warranty)
            array_pop($businessLogic['sale_terms']);

        return $businessLogic;
    }

    /* Sync store Product format with Meli Product format and returns it. */
    public function productUpdateBusinessLogic(Product $product, $category_brand)
    {
        // TODO: tratar +60 chars
        $name = $product->mercadolivre_name . " - No Ofertar";
        if(env("APP_ENV") == "production") {
            $name = $product->mercadolivre_name;
        }

        $attributes = $this->getProductAttributes($product) ?? null;

        if(!$attributes) {
            return redirect()->route('admin-prod-index')->with('error', __("Nenhum atributo foi cadastrado. Acesse Ações -> Editar -> Mercado Livre e cadastre os Atributos para melhorar seu anúncio."));
        }

        $businessLogic = [
            "title" => $name,
            "price" => $product->mercadolivre_price ?? $product->price,
            "currency_id" => "BRL",
            "available_quantity" => $product->stock,
            "sale_terms" => [
                [
                    "id" => "WARRANTY_TYPE",
                    "name" => "Tipo de Garantia",
                    "value_id" => $product->mercadolivre_warranty_type_id,
                    "value_name" => $product->mercadolivre_warranty_type_name,
                    "value_struct" => null,
                    "values" => [
                        [
                            "id" => $product->mercadolivre_warranty_type_id,
                            "name" => $product->mercadolivre_warranty_type_name,
                            "struct" => null,
                        ],
                    ],
                ], [
                    "id" => "WARRANTY_TIME",
                    "value_name" => $product->mercadolivre_warranty_time . ' ' . $product->mercadolivre_warranty_time_unit
                ]
            ],
            "pictures" => $this->getPicturesArray($product),
            "shipping" => [
                "mode" => "custom",
                "local_pick_up" => Pickup::count() > 0 ? true : false,
                "free_shipping" => false, //TODO: Option to change THIS.
                "costs" => $this->getPaidShippings(),
            ],
            "attributes" => $attributes,
        ];

        if($product->mercadolivre_without_warranty)
            array_pop($businessLogic['sale_terms']);

        return $businessLogic;
    }

    // Gets all product attributes from store database.
    public function getProductAttributes(Product $product)
    {
        $attrs = (json_decode($product->mercadolivre_category_attributes, true));

        $arr = [];

        if(!$attrs) {
            return false;
        }

        foreach($attrs as $id => $attr) {
            if(!is_null($attr['value'])){
                array_push($arr, [
                    'id' => $id,
                    'value_name' => $attr['value']
                ]);
            }
        }
        return $arr;
    }

    /*
    *   Returns current warranty for a given product.
    */
    public function getProductWarranties(Product $product)
    {
        return [];
    }

    /*
    * Returns Warranty for a specific Category.
    */
    public function getWarranties(string $categoryId)
    {
        if(!$categoryId)
            return null;

        $meli = self::first();

        $url = config('mercadolivre.api_base_url') . "categories/{$categoryId}/sale_terms";

        $headers = [
            "Authorization: Bearer ". $meli->access_token
        ];

        $warranties = [];

        foreach($this->curlGet($url, $headers) as $saleTerm)
        {
            if($saleTerm->id === "WARRANTY_TYPE" || $saleTerm->id === "WARRANTY_TIME")
                array_push($warranties, (array) $saleTerm);
        }

        return $warranties;
    }

    /*
    * Set all pictures for $product in a specific Array. First it gets the Product Photo then the galleries.
    */
    public function getPicturesArray(Product $product)
    {
        $this->gallery_array = [];
        $galleries = Gallery::where('product_id', $product->id)->get();
        $this->gallery_array[0]['source'] = asset('storage/images/products/' . $product->photo);
        if($galleries) {
            foreach($galleries as $key => $gallery) {
                $this->gallery_array[$key + 1]['source'] = asset('storage/images/galleries/'.$gallery->photo);
            }
        }
        return $this->gallery_array;
    }

    // Find the first paid shipping option to send to Meli.
    // TODO: Review this function and make sure its just what documentation requires.
    public function getPaidShippings()
    {
        $this->shipping_array = [];
        $shippings = Shipping::where('status', 1)->where('price', '>', 0)->get();
        foreach($shippings as $key => $shipping) {
            $this->shipping_array[$key]['description'] = $shipping->title;
            $this->shipping_array[$key]['cost'] = $shipping->price;
        }
        return $this->shipping_array;
    }

    // Find Meli Category ID since a $product->mercadolivre_name is given
    public function getCategoryId($name)
    {
        if(!$name) return null;
        // Get current credentials for Meli
        $meli = self::first();
        // Prepare data for Request
        $url = config('mercadolivre.api_base_url') . "sites/MLB/domain_discovery/search?limit=1&q=" . urlencode($name);

        $headers = [
            "Authorization: Bearer ". $meli->access_token
        ];

        return $this->curlGet($url, $headers)[0]->category_id;
    }

    // Gets all category attributes for product CRUD edit.
    public function getCategoryAttributes($category_id)
    {
        // Get current credentials for Meli
        $meli = self::first();
        // Prepare data for Request
        $url = config('mercadolivre.api_base_url') . "categories/".$category_id."/attributes";

        $headers = [
            "Authorization: Bearer ". $meli->access_token
        ];

        $resp = $this->curlGet($url, $headers);

        if(isset($resp['status'])) {
            return;
        }

        $arr = [];

        foreach($resp as $attribute) {
            $arr[$attribute->id] = [
                'name' => $attribute->name,
                'value' => null,
                'required' => isset($attribute->tags->required) ? true : false,
                'value_type' => $attribute->value_type
            ];

            # campos select / valores fixos
            if(isset($attribute->values))
            {
                $arr[$attribute->id]['values'] = $attribute->values;
            }

            # campos select / valores livres
            if(isset($attribute->allowed_units))
            {
                $arr[$attribute->id]['allowed_units'] = $attribute->allowed_units;
            }

            # max length
            if(isset($attribute->value_max_length))
            {
                $arr[$attribute->id]['value_max_length'] = $attribute->value_max_length;
            }

            if(isset($attribute->tooltip))
            {
                $arr[$attribute->id]['tooltip'] = $attribute->tooltip;
            }

            if(isset($attribute->hint))
            {
                $arr[$attribute->id]['hint'] = $attribute->hint;
            }
        }
        return json_encode($arr);
    }

    public function getListingTypes()
    {
        // Get current credentials for Meli
        $meli = self::first();
        // Prepare data for Request
        $url = config('mercadolivre.api_base_url') . "sites/MLB/listing_types";

        $headers = [
            "Authorization: Bearer ". $meli->access_token
        ];

        $resp = $this->curlGet($url, $headers);

        return $resp;
    }

    public function getListingTypeDetail($listingTypeId)
    {
        // Get current credentials for Meli
        $meli = self::first();
        // Prepare data for Request
        $url = config('mercadolivre.api_base_url') . "sites/MLB/listing_types/". $listingTypeId;

        $headers = [
            "Authorization: Bearer ". $meli->access_token
        ];

        $resp = $this->curlGet($url, $headers);

        return $resp;
    }
}
