<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'country_id',
        'state_id',
        'country_wide',
        'rate'
    ];
    
    
    public function country() {
        return $this->belongsTo('App\Models\Country');
    }
}
