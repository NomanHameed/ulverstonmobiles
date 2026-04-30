<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'color', 'color_hex', 'storage', 'sku',
        'price_modifier', 'price_override', 'stock', 'is_default', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
            'price_override' => 'decimal:2',
            'stock'          => 'int',
            'is_default'     => 'bool',
            'is_active'      => 'bool',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->price_override !== null) {
            return (float) $this->price_override;
        }

        return (float) $this->product->current_price + (float) $this->price_modifier;
    }

    public function getLabelAttribute(): string
    {
        return trim(implode(' · ', array_filter([$this->color, $this->storage])));
    }
}
