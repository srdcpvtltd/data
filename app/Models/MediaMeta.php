<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaMeta extends Model
{
    protected $table = "media_meta";

    protected $fillable = ['size_name', 'path'];

    public $timestamps = false;

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
