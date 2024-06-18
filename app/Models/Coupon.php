<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getOnSubtotalAttribute($value)
    {
        return (string)$value;
    }

    public function getUsedAttribute()
    {
       return OrderMeta::where('value', $this->attributes['code'])->where('key', 'coupon_code')->count();
    }
}
