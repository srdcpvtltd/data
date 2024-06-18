<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use \Plank\Mediable\Media as Mediable;

class Media extends Mediable
{
    protected $appends = ['token', 'name', 'TempUrl', 'ReadableSize'];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if (!$model->meta->count()) {
                return;
            }

            $model->meta->each(function ($item, $key) {
                Storage::disk('public')->delete($item->path);
            });
        });
    }

    public function meta()
    {
        return $this->hasMany(MediaMeta::class);
    }

    public function getUrl($size = ''): string
    {
        if ($size == '') {
            return parent::getUrl(); // TODO: Change the autogenerated stub
        } else {
            $image = $this->meta()->where('size_name', $size)->first();
            if ($image) {
                return url(Storage::url($image->path));
            } else {
                return url('assets/img/thumb.png');
            }
        }
    }

    public function getTempUrlAttribute(): string
    {
        return URL::temporarySignedRoute('download_attachment', now()->addMinutes(180), [$this->token]);
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['filename'] . '.' . $this->attributes['extension'];
    }

    public function getReadableSizeAttribute()
    {
        return $this->readableSize();
    }

    public function getTokenAttribute(): string
    {
        return encrypt($this->getDiskPath());
    }
}
