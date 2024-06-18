<?php

namespace App\Models;

use App\Notifications\User\ResetPasswordNotification;
use App\Notifications\User\VerifyEmailNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    protected $appends = [
        'name'
    ];

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }


    public function meta()
    {
        return $this->hasMany('App\Models\UserMeta', 'user_id');
    }


    public function media()
    {
        return $this->hasMany('App\Models\Media');
    }


    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function getNameAttribute()
    {
        if (isset($this->first_name, $this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return $this->email;
        }
    }

    public function getBillingAddressAttribute()
    {
        return $this->meta->where('key', 'billing_address')->first()->value ?? NULL;
    }


    public function getBillingCityAttribute()
    {
        return $this->meta->where('key', 'billing_city')->first()->value ?? NULL;
    }

    public function getBillingZipAttribute()
    {
        return $this->meta->where('key', 'billing_zip')->first()->value ?? NULL;
    }

    public function getBillingCountryAttribute()
    {
        $id = $this->meta->where('key', 'billing_country')->first()->value ?? "";
        return Country::find($id);
    }

    public function getBillingStateAttribute()
    {
        return $this->meta->where('key', 'billing_state')->first()->value ?? NULL;
    }


    public function getAddressAttribute()
    {
        if (!isset($this->billing_address, $this->billing_city, $this->billing_state, $this->billing_zip_code, $this->billing_country)) {
            return NULL;
        } else {
            return $this->billing_address . '<br>' . $this->billing_city . '<br>' . $this->billing_state . '<br>' . $this->billing_zip_code . '<br>' . $this->billing_country;
        }
    }


    public function getUserMetaAttribute()
    {
        return $this->meta()->pluck('value', 'key')->all();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }
}
