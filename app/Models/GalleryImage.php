<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group', 'image_path', 'caption', 'alt',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'bool'];
    }

    public function scopeForGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }
}
