<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'group', 'type', 'value', 'label', 'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("site_setting:{$key}", function () use ($key, $default) {
            $row = self::where('key', $key)->first();

            if (! $row) {
                return $default;
            }

            return match ($row->type) {
                'bool', 'boolean' => filter_var($row->value, FILTER_VALIDATE_BOOLEAN),
                'int', 'integer' => (int) $row->value,
                'float'          => (float) $row->value,
                'json', 'array'  => json_decode((string) $row->value, true) ?? $default,
                default          => $row->value ?? $default,
            };
        });
    }

    public static function set(string $key, mixed $value, string $type = 'string'): self
    {
        $stored = match ($type) {
            'json', 'array' => json_encode($value),
            'bool', 'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        $row = self::updateOrCreate(
            ['key'   => $key],
            ['value' => $stored, 'type' => $type],
        );

        Cache::forget("site_setting:{$key}");

        return $row;
    }

    protected static function booted(): void
    {
        static::saved(fn (self $row) => Cache::forget("site_setting:{$row->key}"));
        static::deleted(fn (self $row) => Cache::forget("site_setting:{$row->key}"));
    }
}
