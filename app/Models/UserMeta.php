<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = "usermeta";

    protected $fillable = [
        'key',
        'value'
    ];
    
    protected $hidden = ['id', 'user_id'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
