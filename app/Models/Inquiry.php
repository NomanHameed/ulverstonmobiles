<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_id', 'type', 'status',
        'customer_name', 'customer_email', 'customer_phone', 'customer_whatsapp',
        'preferred_contact', 'message',
        'product_id', 'product_variant_id',
        'repair_brand_id', 'repair_model_id', 'repair_issue_id',
        'source', 'meta', 'assigned_to',
        'contacted_at', 'booked_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'meta'         => 'array',
            'contacted_at' => 'datetime',
            'booked_at'    => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public const STATUSES = [
        'new', 'contacted', 'booked', 'in_progress', 'completed', 'cancelled',
    ];

    public const TYPES = ['product', 'repair', 'general'];

    protected static function booted(): void
    {
        static::creating(function (self $inquiry) {
            if (empty($inquiry->tracking_id)) {
                $inquiry->tracking_id = self::generateTrackingId($inquiry->type);
            }
        });
    }

    public static function generateTrackingId(string $type): string
    {
        $prefix = match ($type) {
            'repair'  => 'RPR',
            'general' => 'MSG',
            default   => 'INQ',
        };
        $year   = now()->format('Y');

        do {
            $code = strtoupper(Str::random(6));
            $candidate = "{$prefix}-{$year}-{$code}";
        } while (self::where('tracking_id', $candidate)->exists());

        return $candidate;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function repairBrand(): BelongsTo
    {
        return $this->belongsTo(RepairBrand::class);
    }

    public function repairModel(): BelongsTo
    {
        return $this->belongsTo(RepairModel::class);
    }

    public function repairIssue(): BelongsTo
    {
        return $this->belongsTo(RepairIssue::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function getRouteKeyName(): string
    {
        return 'tracking_id';
    }
}
