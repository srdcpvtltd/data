<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $table = 'order_items';
    protected $guarded = ['id'];
    protected $with = ['meta'];
    public $timestamps = false;


    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }


    public function meta()
    {
        return $this->hasMany('App\Models\OrderItemMeta', 'order_item_id');
    }


    public function name()
    {
        return $this->meta->where('key', '_item_name')->first()->value ?? NULL;
    }


    public function qty()
    {
        return $this->meta->where('key', '_qty')->first()->value ?? 1;
    }


    public function tax_rate()
    {
        return $this->meta->where('key', '_tax_rate')->first()->value ?? 0;
    }


    public function subtotal()
    {
        return $this->meta->where('key', '_subtotal')->first()->value ?? NULL;
    }


    public function total()
    {
        return $this->meta->where('key', '_total')->first()->value ?? NULL;
    }

    public function getDetailsAttribute()
    {
        return Service::find($this->attributes['service_id'])->first();
    }
}
