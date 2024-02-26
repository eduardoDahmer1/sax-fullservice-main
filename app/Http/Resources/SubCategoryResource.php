<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sub_category = [
            "id" => $this->id,
            "category_id" => $this->category_id,
            "status" => $this->status,
            "ref_code" => $this->ref_code
        ];
        
        $translations = $this->translations()->get(['locale','name']);
        foreach ($translations as $translation) {
            $sub_category[$translation->locale]['name'] = $translation->name;
        }

        return $sub_category;
    }
}
