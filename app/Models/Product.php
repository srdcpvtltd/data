<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Plank\Mediable\Mediable;

class Product extends Model implements Buyable
{

    use Sluggable,
        SluggableScopeHelpers,
        Mediable;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if (!$model->hasMedia('gallery')) {
                return;
            }

            $model->getMedia('gallery')->each(function ($item, $key) {
                $item->delete();
            });
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected function features(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? json_decode($value, true) : [],
        );
    }

    public function meta()
    {
        return $this->hasMany('App\Models\ProductMeta', 'product_id');
    }


    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'service_id')->where('parent', 0);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function terms()
    {
        return $this->belongsToMany('App\Models\Term', 'term_relationships')->using(ProductTerm::class);
    }

    public function addons()
    {
        return $this->hasMany(Addon::class);
    }

    /**
     * This function is used to retrieve
     * formatted price with currency symbol
     *
     * @return string
     */
    public function getStartingPriceAttribute()
    {
        if ($this->hasPlans()) {
            return $this->plans()->first()->price;
        }

        return $this->attributes['price'];
    }


    /**
     * This method is used to get delivery time
     * of the service.
     *
     * @return string|null
     */
    public function getTurnAroundAttribute()
    {
        $delivery_time = $this->meta()->where('key', 'delivery_time')->first();
        return (isset($delivery_time->value)) ? $delivery_time->value : NULL;
    }

    /**
     * This method is used to get revisions
     * of the service.
     *
     * @return string|null
     */
    public function getRevisionsAttribute()
    {
        $revisions = $this->meta()->where('key', 'revisions')->first();
        return (isset($revisions->value)) ? $revisions->value : NULL;
    }

    public function getTermListAttribute()
    {
        return $this->terms()->pluck('id')->all();
    }

    public function getProductMetaAttribute()
    {
        return $this->meta()->pluck('value', 'key')->all();
    }

    public function getVariablePricingEnabledAttribute()
    {
        $val = $this->meta()->where('key', 'variable_pricing_enabled')->pluck('value')->first();

        return (isset($val)) ? true : false;
    }

    public function getDefaultPriceAttribute()
    {
        return $this->meta()->where('key', 'default_price_id')->pluck('value')->first();
    }


    public function getVariablePriceAttribute()
    {
        $varPriceEnabled = $this->meta()->where('key', 'variable_pricing_enabled')->pluck('value')->first();
        $varPrice = $this->meta->where('key', 'variable_prices')->first();

        return ($varPriceEnabled != 0) ? $varPrice->value : null;
    }


    public function scopePublished($query)
    {
        $query->where('status', 'publish');
    }

    public function scopeUnPublished($query)
    {
        $query->where('status', 'draft');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'object_id', 'id');
    }

    public function form()
    {
        return $this->hasOne(Form::class, 'id', 'form_id');
    }

    public function hasMeta($key)
    {
        $meta = $this->meta;

        foreach ($meta as $item):
            if ($item->key == $key && !is_null($item->value)) return true;
        endforeach;

        return false;
    }

    public function getMeta($key)
    {
        $meta = $this->meta;

        foreach ($meta as $item):
            if ($item->key == $key) return $item->value;
        endforeach;

        return null;
    }

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
     * Get the description or name of the Buyable item.
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
        return $this->price;
    }

    /**
     * @param string $slug
     * @param array $columns
     * @return mixed
     */
    public static function findBySlug(string $slug, array $columns = ['*'])
    {
        $slug = utf8_uri_encode($slug);

        return static::whereSlug($slug)->first($columns);
    }

    /**
     * @param string $slug
     * @param array $columns
     * @return mixed
     */
    public static function findBySlugOrFail(string $slug, array $columns = ['*'])
    {
        $slug = utf8_uri_encode($slug);

        return static::whereSlug($slug)->firstOrFail($columns);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function hasPlans()
    {
        return $this->plans()->count();
    }

    public function getFeaturedImage()
    {
        return $this->hasMedia('gallery') ?
            $this->firstMedia('gallery')->getUrl('medium') :
            get_placeholder_img();
    }
}
