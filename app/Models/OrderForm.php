<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderForm extends Model
{
    protected $fillable = ['label', 'value', 'type'];

    public function file()
    {
        return $this->belongsTo(Media::class, 'value');
    }
}
