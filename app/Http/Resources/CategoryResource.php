<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $category = [
            "id" => $this->id,
            "status" => $this->status,
            "ref_code" => $this->ref_code
        ];
        
        $translations = $this->translations()->get(['locale','name']);
        foreach ($translations as $translation) {
            $category[$translation->locale]['name'] = $translation->name;
        }

        return $category;
    }
}
