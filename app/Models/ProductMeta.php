<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model {

    protected $fillable = [
        'key',
        'value'
    ];
    protected $table = 'product_meta';
    protected $primaryKey = 'product_meta_id';
    public $timestamps = false;

    public function product() {

        return $this->belongsTo('App\Models\Product');
    }

    public function getValueAttribute(){

        if (is_serialized($this->attributes['value'])) {
            return unserialize($this->attributes['value']);
        }

        return $this->attributes['value'];
    }

}
