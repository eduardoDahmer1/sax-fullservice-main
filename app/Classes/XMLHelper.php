<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use App\Models\Generalsetting;
use App\Models\Product;

class XMLHelper
{
    public function updateComprasParaguai()
    {
        $main_store = Generalsetting::first();
        $comprasparaguai_store = Generalsetting::find($main_store->store_comprasparaguai);
        $comprasparaguai_locale = $comprasparaguai_store->defaultLang->locale;

        //$products = Product::where('status',1)->get();  //--- Not working with large database ?!
        $products = DB::table('products')
                ->select(
                    'products.slug as slug',
                    'products.ref_code as ref_code',
                    'products.price as price',
                    'products.stock as stock',
                    'products.photo as photo',
                    'product_translations.name as name',
                    'product_translations.details as details',
                    'brands.name as brand'
                )
                ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->join('product_store', 'products.id', '=', 'product_store.product_id')
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->where('product_store.store_id', '=', $comprasparaguai_store->id)
                ->where('product_translations.locale', '=', $comprasparaguai_locale)
                ->where('products.status', 1)
                ->Where(function ($query) {
                    return $query->where('products.stock', '>', 0)
                        ->orWhereNull('products.stock');
                })
                ->get();
        //clock($products);

        $xml = new DOMDocument("1.0", "UTF-8");
        $rss = $xml->createElement("rss");
        $rss_create = $xml->appendChild($rss);
        $rss_create->setAttribute("version", "2.0");

        $channel = $xml->createElement("channel");
        $channel_create = $rss->appendChild($channel);
        $store_name = $xml->createElement("title", $comprasparaguai_store->translate($comprasparaguai_locale)->title);
        $store_name_create = $channel->appendChild($store_name);
        $store_url = $comprasparaguai_store->domain;
        if (!filter_var($store_url, FILTER_VALIDATE_URL)) {
            $store_url = "https://".$store_url;
        }
        $xml_store_url = $xml->createElement("link", $store_url);
        $xml_store_url_create = $channel->appendChild($xml_store_url);
        $store_description = $xml->createElement("description", $comprasparaguai_store->footer);
        $store_description_create  = $channel->appendChild($store_description);

        $valor = '';

        $disponivel = 0;
        foreach ($products as $product) {
            $disponivel = "em estoque";
            if (!empty($product->stock) && $product->stock == 0) {
                $disponivel = "fora de estoque";
            }

            $valor = number_format($product->price, 2, '.', '');
            $photo = ($product->photo ? $product->photo : "{$store_url}/assets/images/noimage.png");

            if ($product->photo != "noimage.jpg" && !filter_var($product->photo, FILTER_VALIDATE_URL)) {
                if (!empty($product->photo) && file_exists(public_path()."/storage/images/products/".$product->photo)) {
                    $photo = $store_url."/storage/images/products/".$product->photo;
                } else {
                    if (is_dir(public_path()."/storage/images/ftp/products_images/{$product->ref_code}")) {
                        $files = scandir(public_path()."/storage/images/ftp/products_images/".$product->ref_code);
                        if (isset($files[2])) {
                            $photo = $store_url . "/storage/images/ftp/products_images/" . $product->ref_code . "/" .  $files[2];
                        }
                    }
                }
            }

            $item                    = $xml->createElement("item");
            $item_create             = $channel_create->appendChild($item);
            $title                   = $xml->createElement("title", htmlspecialchars($product->name));
            $title_create            = $item->appendChild($title);
            $link                    = $xml->createElement("link", $store_url."/item/".$product->slug);
            $link_create             = $item->appendChild($link);
            //$description             = $xml->createElement("description", htmlspecialchars(strip_tags($product->details))); //--- invalid chars
            $description             = $xml->createElement("description", "");
            $description_create      = $item->appendChild($description);
            $code                    = $xml->createElement("codigo", $product->ref_code);
            $code_create             = $item->appendChild($code);
            $price                   = $xml->createElement("preco", ($valor?:"0"));
            $price_create            = $item->appendChild($price);
            $image                   = $xml->createElement("link_imagem", $photo);
            $imageCreate             = $item->appendChild($image);
            $availability            = $xml->createElement("disponibilidade", $disponivel);
            $availability_create     = $item->appendChild($availability);
            $promotion               = $xml->createElement("preco_normal_sem_liquidacao", $valor);
            $promotion_create        = $item->appendChild($promotion);
            $brand                   = $xml->createElement("marca", ($product->brand? htmlspecialchars($product->brand):''));
            $brand_create            = $item->appendChild($brand);
        }

        @@unlink(public_path()."/assets/files/comprasparaguai.xml");
        $xml->save(public_path()."/assets/files/comprasparaguai.xml");

        $msg = __('Compras Paraguai atualizado com sucesso.');
        return response()->json($msg);
    }

    public function updateLojaGoogle()
    {
        $main_store = Generalsetting::first();
        $comprasparaguai_store = Generalsetting::find($main_store->store_comprasparaguai);
        $comprasparaguai_locale = $comprasparaguai_store->defaultLang->locale;

        //$products = Product::where('status',1)->get();  //--- Not working with large database ?!
        $products = DB::table('products')
                ->select(
                    'products.id as id',
                    'products.product_condition as condition',
                    'products.slug as slug',
                    'products.mpn as mpn',
                    'products.gtin as gtin',
                    'products.ref_code as ref_code',
                    'products.price as price',
                    'products.stock as stock',
                    'products.photo as photo',
                    'product_translations.name as name',
                    'product_translations.details as details',
                    'brands.name as brand',
                    'products.category_id as google_product_category'
                )
                ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->join('product_store', 'products.id', '=', 'product_store.product_id')
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->where('product_store.store_id', '=', $comprasparaguai_store->id)
                ->where('product_translations.locale', '=', $comprasparaguai_locale)
                ->where('products.status', 1)->get();

        //clock($products);

        $xml = new DOMDocument("1.0", "UTF-8");
        $rss = $xml->createElement("rss");
        $rss_create = $xml->appendChild($rss);
        $rss_create->setAttribute("version", "2.0");
        $rss_create->setAttribute("xmlns:g", "http://base.google.com/ns/1.0");

        $channel = $xml->createElement("channel");
        $channel_create = $rss->appendChild($channel);
        $store_name = $xml->createElement("title", $comprasparaguai_store->translate($comprasparaguai_locale)->title);
        $store_name_create = $channel->appendChild($store_name);
        $store_url = $comprasparaguai_store->domain;
        if (!filter_var($store_url, FILTER_VALIDATE_URL)) {
            $store_url = "https://".$store_url;
        }
        $xml_store_url = $xml->createElement("link", $store_url);
        $xml_store_url_create = $channel->appendChild($xml_store_url);
        $store_description = $xml->createElement("description", $comprasparaguai_store->footer);
        $store_description_create  = $channel->appendChild($store_description);

        $valor = '';

        $disponivel = 0;
        foreach ($products as $product) {
            $disponivel = "in stock";
            if (!empty($product->stock) && $product->stock == 0) {
                $disponivel = "Out Of Stock!";
            }

            $brand = $product->brand;

            if (empty($product->brand)) {
                $brand = $main_store->title;
            }

            $valor = number_format($product->price, 2, '.', '');
            $photo = "";
            if (!empty($product->photo) && !filter_var($product->photo, FILTER_VALIDATE_URL)) {
                $photo = $store_url."/storage/images/products/".$product->photo;
            }

            $item                    = $xml->createElement("item");
            $item_create             = $channel_create->appendChild($item);
            $id                      = $xml->createElement("g:id", htmlspecialchars($product->id));
            $id_create               = $item->appendChild($id);
            $title                   = $xml->createElement("g:title", htmlspecialchars($product->name));
            $title_create            = $item->appendChild($title);
            $description_string = strip_tags(html_entity_decode($product->details));
            $content = str_replace("&nbsp;", "", $description_string);
            $description             = $xml->createElement("g:description", htmlspecialchars($content));
            $description_create      = $item->appendChild($description);
            $condition               = $xml->createElement("g:condition", "new");
            $condition_create        = $item->appendChild($condition);
            $link                    = $xml->createElement("g:link", $store_url."/item/".$product->slug);
            $link_create             = $item->appendChild($link);
            $price1                   = $xml->createElement("g:price", $valor . " " . "BRL");
            $price_create            = $item->appendChild($price1);
            $image                   = $xml->createElement("g:image_link", $photo);
            $imageCreate             = $item->appendChild($image);
            $availability            = $xml->createElement("g:availability", $disponivel);
            $availability_create     = $item->appendChild($availability);
            $marca                   = $xml->createElement("g:brand", htmlspecialchars($brand));
            $brand_create            = $item->appendChild($marca);
            $gtin                    = $xml->createElement("g:gtin", htmlspecialchars($product->gtin));
            $gtin_create             = $item->appendChild($gtin);
            $mpn                     = $xml->createElement("g:MPN", htmlspecialchars($product->mpn));
            $mpn_create              = $item->appendChild($mpn);
            $adult                   = $xml->createElement("g:adult", "no");
            $adult_create            = $item->appendChild($adult);
            $multipack               = $xml->createElement("g:multipack", 0);
            $multipack_create        = $item->appendChild($multipack);
            $is_bundle               = $xml->createElement("g:is_bundle", "no");
            $is_bundle_create        = $item->appendChild($is_bundle);
            $age_group               = $xml->createElement("g:age_group", "");
            $age_group_create        = $item->appendChild($age_group);
            $color                   = $xml->createElement("g:color", "");
            $color_create            = $item->appendChild($color);
            $gender                  = $xml->createElement("g:gender", "unisex");
            $gender_create           = $item->appendChild($gender);
            $material                = $xml->createElement("g:material", "");
            $material_create         = $item->appendChild($material);
            $pattern                 = $xml->createElement("g:pattern", "");
            $pattern_create          = $item->appendChild($pattern);
            $size                    = $xml->createElement("g:size", "");
            $size_create             = $item->appendChild($size);
            $item_group_id           = $xml->createElement("g:item_group_id", htmlspecialchars($product->gtin));
            $item_group_id_create    = $item->appendChild($item_group_id);
            $identifier_exists       = $xml->createElement("g:identifier_exists", "no");
            $identifier_exists_create = $item->appendChild($identifier_exists);
            $google_product_category = $xml->createElement("g:google_product_category", htmlspecialchars($product->google_product_category));
            $google_product_category_create = $item->appendChild($google_product_category);
        }

        @@unlink(public_path()."/assets/files/googleshopping.xml");
        $xml->save(public_path()."/assets/files/googleshopping.xml");
    }

    public function updateLojaFacebook()
    {
        $main_store = Generalsetting::first();
        $comprasparaguai_store = Generalsetting::find($main_store->store_comprasparaguai);
        $comprasparaguai_locale = $comprasparaguai_store->defaultLang->locale;

        //$products = Product::where('status',1)->get();  //--- Not working with large database ?!
        $products = DB::table('products')
                ->select(
                    'products.id as id',
                    'products.product_condition as condition',
                    'products.slug as slug',
                    'products.mpn as mpn',
                    'products.gtin as gtin',
                    'products.ref_code as ref_code',
                    'products.price as price',
                    'products.stock as stock',
                    'products.photo as photo',
                    'product_translations.name as name',
                    'product_translations.details as details',
                    'brands.name as brand',
                    'products.category_id as google_product_category'
                )
                ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
                ->join('product_store', 'products.id', '=', 'product_store.product_id')
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->where('product_store.store_id', '=', $comprasparaguai_store->id)
                ->where('product_translations.locale', '=', $comprasparaguai_locale)
                ->where('products.status', 1)->get();

        //clock($products);

        $xml = new DOMDocument("1.0", "UTF-8");
        $rss = $xml->createElement("rss");
        $rss_create = $xml->appendChild($rss);
        $rss_create->setAttribute("version", "2.0");

        $channel = $xml->createElement("channel");
        $channel_create = $rss->appendChild($channel);
        $store_name = $xml->createElement("title", $comprasparaguai_store->translate($comprasparaguai_locale)->title);
        $store_name_create = $channel->appendChild($store_name);
        $store_url = $comprasparaguai_store->domain;
        if (!filter_var($store_url, FILTER_VALIDATE_URL)) {
            $store_url = "https://".$store_url;
        }
        $xml_store_url = $xml->createElement("link", $store_url);
        $xml_store_url_create = $channel->appendChild($xml_store_url);
        $store_description = $xml->createElement("description", $comprasparaguai_store->footer);
        $store_description_create  = $channel->appendChild($store_description);

        $valor = '';

        $disponivel = 0;
        foreach ($products as $product) {
            $disponivel = "in stock";
            if (!empty($product->stock) && $product->stock == 0) {
                $disponivel = "Out Of Stock!";
            }

            $brand = $product->brand;

            if (empty($product->brand)) {
                $brand = $main_store->title;
            }

            $valor = number_format($product->price, 2, '.', '');
            $photo = "";
            if (!empty($product->photo) && !filter_var($product->photo, FILTER_VALIDATE_URL)) {
                $photo = $store_url."/storage/images/products/".$product->photo;
            }

            $item                    = $xml->createElement("item");
            $item_create             = $channel_create->appendChild($item);
            $id                      = $xml->createElement("id", htmlspecialchars($product->id));
            $id_create               = $item->appendChild($id);
            $title                   = $xml->createElement("title", htmlspecialchars($product->name));
            $title_create            = $item->appendChild($title);
            $description_string = strip_tags(html_entity_decode($product->details));
            $content = str_replace("&nbsp;", "", $description_string);
            $description             = $xml->createElement("description", htmlspecialchars($content));
            $description_create      = $item->appendChild($description);
            $condition               = $xml->createElement("condition", "new");
            $condition_create        = $item->appendChild($condition);
            $link                    = $xml->createElement("link", $store_url."/item/".$product->slug);
            $link_create             = $item->appendChild($link);
            $price1                   = $xml->createElement("price", ($valor));
            $price_create            = $item->appendChild($price1);
            $image                   = $xml->createElement("image_link", $photo);
            $imageCreate             = $item->appendChild($image);
            $availability            = $xml->createElement("availability", $disponivel);
            $availability_create     = $item->appendChild($availability);
            $marca                   = $xml->createElement("brand", htmlspecialchars($brand));
            $brand_create            = $item->appendChild($marca);
            $gtin                    = $xml->createElement("gtin", htmlspecialchars($product->gtin));
            $gtin_create             = $item->appendChild($gtin);
            $mpn                     = $xml->createElement("MPN", htmlspecialchars($product->mpn));
            $mpn_create                    = $item->appendChild($mpn);
            $adult                   = $xml->createElement("adult", "no");
            $adult_create            = $item->appendChild($adult);
            $multipack               = $xml->createElement("multipack", 0);
            $multipack_create        = $item->appendChild($multipack);
            $is_bundle               = $xml->createElement("is_bundle", "no");
            $is_bundle_create        = $item->appendChild($is_bundle);
            $age_group               = $xml->createElement("age_group", "");
            $age_group_create        = $item->appendChild($age_group);
            $color                   = $xml->createElement("color", "");
            $color_create            = $item->appendChild($color);
            $gender                  = $xml->createElement("gender", "unisex");
            $gender_create           = $item->appendChild($gender);
            $material                = $xml->createElement("material", "");
            $material_create         = $item->appendChild($material);
            $pattern                 = $xml->createElement("pattern", "");
            $pattern_create          = $item->appendChild($pattern);
            $size                    = $xml->createElement("size", "");
            $size_create             = $item->appendChild($size);
            $item_group_id           = $xml->createElement("item_group_id", htmlspecialchars($product->gtin));
            $item_group_id_create    = $item->appendChild($item_group_id);
            $identifier_exists       = $xml->createElement("identifier_exists", "no");
            $identifier_exists_create = $item->appendChild($identifier_exists);
            $google_product_category = $xml->createElement("google_product_category", htmlspecialchars($product->google_product_category));
            $google_product_category_create = $item->appendChild($google_product_category);
        }

        @@unlink(public_path()."/assets/files/facebookbusiness.xml");
        $xml->save(public_path()."/assets/files/facebookbusiness.xml");
    }
}
