<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RepairIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'icon',
        'base_price', 'estimated_minutes', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'base_price'        => 'decimal:2',
            'estimated_minutes' => 'int',
            'is_active'         => 'bool',
        ];
    }

    public function models(): BelongsToMany
    {
        return $this->belongsToMany(RepairModel::class, 'repair_model_issue')
            ->withPivot(['price', 'estimated_minutes'])
            ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
