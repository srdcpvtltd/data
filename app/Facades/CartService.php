<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class CartService extends Facade
{
    public static function getFacadeAccessor()
    {
        return \App\Services\CartService::class;
    }
}
