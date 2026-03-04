<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'logo_url' => $this->logo_path ? asset('storage/' . $this->logo_path) : null,
            'address' => $this->address,
            'phone' => $this->phone,
            'website' => $this->website,
            'is_active' => $this->is_active,
            'menus' => MenuResource::collection($this->whenLoaded('menus')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
