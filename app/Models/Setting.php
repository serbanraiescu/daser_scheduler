<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        try {
            $result = self::updateOrCreate(['key' => $key], ['value' => $value]);
            
            if (str_contains($key, 'license_')) {
                \Log::info("Setting updated: {$key} = " . (str_contains($key, 'password') || str_contains($key, 'key') ? '***' : $value));
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error("Failed to save setting {$key}: " . $e->getMessage());
            return false;
        }
    }
}
