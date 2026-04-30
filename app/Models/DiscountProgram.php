<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'eligibility', 'description',
        'icon', 'image_path', 'cta_label', 'cta_url',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'bool'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
