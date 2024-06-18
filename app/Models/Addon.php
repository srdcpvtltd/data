<?php

namespace App\Models;

use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model implements Buyable
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'product_id'
    ];

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        return $this->name;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice($options = null)
    {

    }
}
