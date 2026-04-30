<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'logo_path', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'bool'];
    }

    public function models(): HasMany
    {
        return $this->hasMany(RepairModel::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
