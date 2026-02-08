<?php

namespace Escalated\Laravel\Models;

use Escalated\Laravel\Escalated;
use Illuminate\Database\Eloquent\Model;

class EscalatedSettings extends Model
{
    protected $guarded = ['id'];

    public function getTable(): string
    {
        return Escalated::table('settings');
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    /**
     * Get a boolean setting value.
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        $value = static::get($key);

        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get an integer setting value.
     */
    public static function getInt(string $key, int $default = 0): int
    {
        $value = static::get($key);

        return $value !== null ? (int) $value : $default;
    }

    /**
     * Check if guest tickets are enabled.
     */
    public static function guestTicketsEnabled(): bool
    {
        return static::getBool('guest_tickets_enabled', true);
    }
}
