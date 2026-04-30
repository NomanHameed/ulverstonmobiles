<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RepairModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_brand_id', 'name', 'slug', 'image_path',
        'release_year', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'bool'];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(RepairBrand::class, 'repair_brand_id');
    }

    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(RepairIssue::class, 'repair_model_issue')
            ->withPivot(['price', 'estimated_minutes'])
            ->withTimestamps();
    }
}
