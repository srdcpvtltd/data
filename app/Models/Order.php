<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class Order extends Model
{
    use Mediable;

    protected $fillable = ['status', 'user_id'];
    protected $appends = ['status_text'];
    protected $with = ['items', 'order_details'];

    public function order_details()
    {
        return $this->hasMany('App\Models\OrderMeta', 'order_id');
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function items()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }


    public function currency()
    {
        return $this->getMeta('_order_currency') ?? setting('currency');
    }


    public function subtotal()
    {
        return ch_format_price($this->order_details->where('key', '_order_subtotal')->first()->value, $this->currency()) ?? NULL;
    }


    public function tax_rate()
    {
        return $this->order_details->where('key', '_tax_rate')->first()->value ?? NULL;
    }


    public function taxTotal()
    {
        $tax = $this->order_details->where('key', '_tax_total')->first()->value ?? 0;
        return ch_format_price($tax, $this->currency());
    }

    public function totalFormatted()
    {
        if ($this->hasMeta('_order_total')) {
            return ch_format_price($this->getMeta('_order_total'), $this->currency());
        }
    }

    public function total()
    {
        return $this->getMeta('_order_total');
    }

    public function transactionId()
    {
        return $this->getMeta('_transaction_id');
    }

    public function paymentMethod()
    {
        return $this->getMeta('_payment_method');
    }

    public function getAddonsAttribute()
    {
        $addons = $this->items()->first()->meta()->where('key', '_addons')->first();
        return (!is_null($addons)) ? $addons->value : NULL;
    }

    public function getServiceAttribute()
    {
        $service = $this->items()
            ->where('item_type', 'product')
            ->orWhere('item_type', 'plan')->first()
            ->meta()
            ->where('key', '_item_name')->first();

        return (!is_null($service)) ? $service->value : NULL;
    }


    public function messages()
    {
        return $this->hasMany('App\Models\Comment', 'object_id')
            ->with(['user', 'media'])
            ->where('type', 'message')
            ->orderBy('created_at', 'ASC');
    }

    public function UnreadMessagesCount($id)
    {
        return $this->messages()
            ->where('seen_at', null)
            ->where('to_id', $id)
            ->count();
    }

    public function lastMessage()
    {
        return $this->messages->last()->content ?? '';
    }

    public function getStatusTextAttribute()
    {
        $status = $this->attributes['status'];
        if ($status == 'pending') {
            return '<span class="badge order-status bg-info">Pending payment</span>';
        } elseif ($status == 'processing') {
            return '<span class="badge order-status bg-primary">'.ucfirst($this->attributes['status']).'</span>';
        } elseif ($status == 'completed') {
            return '<span class="badge order-status bg-success">'.ucfirst($this->attributes['status']).'</span>';
        } elseif ($status == 'refunded') {
            return '<span class="badge order-status bg-warning">'.ucfirst($this->attributes['status']).'</span>';
        } elseif ($status == 'cancelled' || $status == 'failed') {
            return '<span class="badge order-status bg-danger">'.ucfirst($this->attributes['status']).'</span>';
        }
    }


    public function feedback($user_id)
    {
        return $this->hasMany('App\Models\Comment', 'object_id')->where('type', 'feedback')->where('user_id', $user_id)->get()->first();
    }

    public function customFields()
    {
        return $this->hasMany(OrderForm::class);
    }

    public function hasMeta($key)
    {
        $meta = $this->order_details;

        foreach ($meta as $item):
            if ($item->key == $key) return true;
        endforeach;

        return false;
    }

    public function getMeta($key)
    {
        $meta = $this->order_details;

        foreach ($meta as $item):
            if ($item->key == $key) return $item->value;
        endforeach;

        return null;
    }


    public function state()
    {
        return $this->hasOneThrough(State::class, OrderMeta::class, 'order_id', 'id', 'id', 'value')
            ->where('key', 'billing_state');
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, OrderMeta::class, 'order_id', 'id', 'id', 'value')
            ->where('key', 'billing_country');
    }

}
