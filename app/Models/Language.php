<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'direction',
        'default',
        'enabled',
    ];

    public static function findByLocale(string $locale)
    {
        return self::where('locale', $locale)->first();
    }

    public function phrases(): HasMany
    {
        return $this->hasMany(LanguagePhrase::class, 'lang_id');
    }
}
