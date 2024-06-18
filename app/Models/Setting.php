<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{

    public $timestamps = false;
    protected $fillable = ['key', 'value'];

    public static function updateSettings($settings)
    {
        $dot_settings = Arr::dot($settings);

        foreach ($dot_settings as $key => $value) {
            self::UpdateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget('settings');
    }
}
