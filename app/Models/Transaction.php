<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_number',
        'invoice_id',
        'method'
    ];


    public function order() {
        return $this->belongsTo('App\Models\Order');
    }
}
