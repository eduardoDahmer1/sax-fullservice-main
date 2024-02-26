<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product = [
            "id" => $this->id,
            "sku" => $this->sku,
            "product_type" => $this->product_type,
            "category_id" => $this->category_id,
            "subcategory_id" => $this->subcategory_id,
            "childcategory_id" => $this->childcategory_id,
            "price" => $this->price,
            "previous_price" => $this->previous_price,
            "stock" => $this->stock,
            "name" => $this->name,
            "size" => is_array($this->size) ? $this->size : [],
            "size_qty" => is_array($this->size_qty) ? $this->size_qty : [],
            "size_price" => is_array($this->size_price) ? $this->size_price : [],
            "status" => $this->status,
            "type" => $this->type,
            "measure" => $this->measure,
            "brand_id" => $this->brand_id,
            "ref_code" => $this->ref_code,
            "ref_code_int" => $this->ref_code_int,
            "weight" => $this->weight,
            "width" => $this->width,
            "height" => $this->height,
            "length" => $this->length,
            "external_name" => $this->external_name,
            "stores" => $this->stores()->get()->pluck('id')->toArray(),
            "thumbnail" => $this->thumbnail,
            "product_size" => $this->product_size,
            "color" => is_array($this->color) ? $this->color : [],
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
        
        $translations = $this->translations()->get(['locale','name','details']);
        foreach ($translations as $translation) {
            $product[$translation->locale]['name'] = $translation->name;
            $product[$translation->locale]['details'] = $translation->details;
        }

        return $product;
    }
}
