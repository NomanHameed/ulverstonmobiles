<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'eyebrow', 'intro', 'body',
        'hero_image_path', 'meta_title', 'meta_description',
        'is_published', 'show_in_nav', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'bool',
            'show_in_nav'  => 'bool',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
