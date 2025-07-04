<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'string', string $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }

    /**
     * Cast value to appropriate type
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Check if site password protection is enabled
     */
    public static function isSitePasswordEnabled(): bool
    {
        return static::get('site_password_enabled', false);
    }

    /**
     * Get site password
     */
    public static function getSitePassword(): string
    {
        return static::get('site_password', '');
    }

    /**
     * Get site password message
     */
    public static function getSitePasswordMessage(): string
    {
        return static::get('site_password_message', 'This site is password protected. Please enter the password to continue.');
    }

    /**
     * Check if site login is required
     */
    public static function isSiteLoginRequired(): bool
    {
        return static::get('site_login_required', false);
    }
}
