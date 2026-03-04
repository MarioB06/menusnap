<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'features' => $this->features ?? [],
            'max_menus' => $this->max_menus,
            'max_dishes' => $this->max_dishes,
            'max_tables' => $this->max_tables,
        ];
    }
}
