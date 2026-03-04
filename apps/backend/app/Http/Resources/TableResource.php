<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'label' => $this->label,
            'uuid' => $this->uuid,
            'qr_code_url' => $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null,
            'menu_url' => url("/menu/{$this->restaurant->slug}/{$this->uuid}"),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
