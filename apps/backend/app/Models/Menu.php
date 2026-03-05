<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Menu $menu) {
            if (empty($menu->uuid)) {
                $menu->uuid = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'is_active',
        'sort_order',
        'uuid',
        'qr_code_path',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
