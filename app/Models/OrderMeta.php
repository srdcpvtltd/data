<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMeta extends Model
{

    protected $table = "order_meta";

    protected $fillable = [
        'key',
        'value'
    ];
    
    protected $hidden = ['id', 'order_id'];


    public $timestamps = false;


    public function order() {
        return $this->belongsTo('App\Models\Order');
    }


    public function getValueAttribute(){
        if (is_serialized($this->attributes['value'])) {
            return unserialize($this->attributes['value']);
        }
        return $this->attributes['value'];
    }

    public function getAddressAttribute() {
        if ( !$this->billing_address && !$this->billing_city && !$this->billing_state && !$this->billing_zip_code && !$this->billing_country ) {
            return NULL;
        }  else {
            return $this->billing_address . '<br>' . $this->billing_city . '<br>' .$this->billing_state . '<br>' .$this->billing_zip . '<br>' .$this->billing_country;
        }
    }
}
