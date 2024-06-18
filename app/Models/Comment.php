<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class Comment extends Model
{
    use Mediable;

    protected $fillable = [
        'content',
        'type',
        'parent',
        'rating',
        'user_id',
        'to_id',
    ];


    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    // Comment model
    public function replies()
    {
        return $this->hasMany('App\Models\Comment', 'parent');
    }
}
