<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Term extends Model
{

    use Sluggable,
        SluggableScopeHelpers;

    protected $fillable = [
        'name',
        'parent',
        'taxonomy',
        'description',
        'route',
        'product_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model){
            $modified = $model->getDirty();

            if (!isset($modified['name'], $modified['slug'])) {
                return;
            }

            $oldSlug = $model->getOriginal('slug');
            $route = $model->getParentSlug();

            $model->route = str_replace($oldSlug, $modified['slug'], $route);
        });

        static::updated(function ($model){
            $modified = $model->getDirty();

            if (!isset($modified['parent'])) {
                return;
            }

            $route = $model->getParentSlug();

            static::find($model->id)->update(['route' => $route]);

        });
    }

    protected $table = 'terms';

    public $timestamps = false;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function services()
    {
        return $this->belongsToMany('App\Models\Product', 'term_relationships')->using(ProductTerm::class);
    }

    public function childs()
    {
        return $this->hasMany(self::class, 'parent', 'id')->with('childs');
    }

    public function getRouteAttribute()
    {
        if (isset($this->attributes['route']) && $this->attributes['route'] !== null) {
            return url('category/' . $this->attributes['route']);
        }

        $cat_route = $this->getParentSlug();

        if (Schema::hasColumn('terms', 'route')) {
            $this->route = $cat_route;

            $this->save();

            return $this->attributes['route'];
        }

        return url('category/' . $cat_route);
    }

    public function getProductCountAttribute()
    {
        if (isset($this->attributes['product_count']) && $this->attributes['product_count'] !== null) {
            return $this->attributes['product_count'];
        }
    }

    public function getParentSlug()
    {
        $parents = DB::select('SELECT T2.slug
                        FROM (
                            SELECT
                                @r AS _id,
                                (SELECT @r := parent FROM ch_terms WHERE id = _id) AS parent,
                                @l := @l + 1 AS lvl
                            FROM
                                (SELECT @r := ?, @l := 0) vars,
                                ch_terms h
                            WHERE @r <> 0) T1
                        JOIN ch_terms T2
                        ON T1._id = T2.id
                        ORDER BY T1.lvl DESC', [$this->getKey()]);

        return collect($parents)->pluck('slug')->implode('/');
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
}
