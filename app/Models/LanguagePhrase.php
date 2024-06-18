<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguagePhrase extends Model
{
    protected $fillable = [
        'locale',
        'group',
        'key',
        'value',
    ];

    public function lang()
    {
        return $this->belongsTo(Language::class, 'locale');
    }
}
