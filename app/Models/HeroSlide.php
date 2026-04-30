<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'eyebrow', 'headline', 'subheadline',
        'image_path', 'mobile_image_path', 'video_path',
        'primary_cta_label', 'primary_cta_url',
        'secondary_cta_label', 'secondary_cta_url',
        'theme', 'text_align',
        'is_active', 'sort_order',
        'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order');
    }
}
