<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function features(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => json_decode($value, true),
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
