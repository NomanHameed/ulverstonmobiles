<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'message', 'link_url', 'link_label', 'icon',
        'background_color', 'text_color',
        'is_active', 'is_dismissible',
        'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'      => 'bool',
            'is_dismissible' => 'bool',
            'starts_at'      => 'datetime',
            'ends_at'        => 'datetime',
        ];
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }
}
