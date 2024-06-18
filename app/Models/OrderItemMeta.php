<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItemMeta extends Model{
    
    protected $table = 'order_itemmeta';
    
    protected $fillable = ['key', 'value'];


    public $timestamps = false;
    
    public function item() {
        return $this->belongsTo('App\Models\OrderItem');
    }


    public function getValueAttribute(){

        if (is_serialized($this->attributes['value'])) {
            return unserialize($this->attributes['value']);
        }
        return $this->attributes['value'];
    }
    
    
}
