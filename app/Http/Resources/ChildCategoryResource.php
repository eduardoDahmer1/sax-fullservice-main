<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $child_category = [
            "id" => $this->id,
            "subcategory_id" => $this->subcategory_id,
            "status" => $this->status,
            "ref_code" => $this->ref_code
        ];
        
        $translations = $this->translations()->get(['locale','name']);
        foreach ($translations as $translation) {
            $child_category[$translation->locale]['name'] = $translation->name;
        }

        return $child_category;
    }
}
