<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name'];

    public function service()
    {
        return $this->belongsTo(Product::class, 'object_id', 'id');
    }
}
