<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use App\Models\Addon;
use App\Models\Plan;
use App\Models\Product;
use App\Repositories\CouponRepository;
use App\Repositories\ProductRepository;
use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;
use Illuminate\Support\Collection;
use App\Models\Tax;
use phpDocumentor\Reflection\Types\Parent_;

/**
 * Description of CartService
 *
 * @author rk
 */
class CartService extends Cart
{
    public function refresh()
    {
        if ( \Auth::check() ) {
            $user       = \Auth::user();
            $state_id = $user->billing_state ?? null;
            $country_id = $user->billing_country->id ?? null;

            $this->updateTax($country_id, $state_id);
        }
    }

    /**
     * @param Product $product
     * @param int $int
     * @param array $options
     * @return CartItem
     */
    public function addToCart($id, $name, $int, $price, $options = []) : CartItem
    {
        if (isset($options['type'], $options['model'])) {
            $namePrefix = '';

            if ($options['type'] === 'plan') {
                $model = $options['model'];
                $namePrefix = $model->product->name . ' &mdash; ';
            } elseif ($options['type'] === 'addon') {
                $namePrefix = 'Addon &mdash; ';
            }

            $name = $namePrefix . $name;
        }

        return parent::add($id, $name, $int, $price, $options);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCartItems() : Collection
    {
        return parent::content();
    }

    /**
     * @param string $rowId
     *
     * @throws ProductInCartNotFoundException
     */
    public function removeToCart(string $rowId)
    {
        try {
            parent::remove($rowId);
        } catch (InvalidRowIDException $e) {
            throw $e;
        }
    }

    /**
     * Count the items in the cart
     *
     * @return int
     */
    public function countItems() : int
    {
        return parent::count();
    }

    /**
     * Get the sub total of all the items in the cart
     *
     * @param int $decimals
     * @return float
     */
    public function getSubTotal(int $decimals = 2)
    {
        return parent::subtotal($decimals, '.', '');
    }

    public function getSubTotalFormatted()
    {
        return ch_format_price($this->getSubTotal());
    }

    /**
     * Get the final total of all the items in the cart minus tax
     *
     * @param int $decimals
     * @param float $shipping
     * @return float
     */
    public function getTotal(int $decimals = 2, $shipping = 0.00)
    {
        $total = parent::total($decimals, '.', '', $shipping);

        if (session('discounted')) {
            return $total - session('discount_amount');
        }

        return $total;
    }

    public function getTotalFormatted()
    {
        return ch_format_price($this->getTotal());
    }

    /**
     * @param string $rowId
     * @param int $quantity
     * @return CartItem
     */
    public function updateQuantityInCart(string $rowId, int $quantity) : CartItem
    {
        return parent::update($rowId, $quantity);
    }

    /**
     * Return the specific item in the cart
     *
     * @param string $rowId
     * @return \Gloudemans\Shoppingcart\CartItem
     */
    public function findItem(string $rowId) : CartItem
    {
        return parent::get($rowId);
    }

    /**
     * Returns the tax
     *
     * @param int $decimals
     * @return float
     */
    public function getTax(int $decimals = 2)
    {
        return parent::tax($decimals);
    }

    public function getTaxFormatted()
    {
        return ch_format_price($this->getTax());
    }

    public function updateTax($country_id = null, $state_id = null)
    {
        // check if tax is enebaled.
        if (setting('taxes.enabled', 'no') == 'no') {
            return;
        }

        $tax_rate = 0;

        // Check if user is logged in.

        $tax_row = null;

        if ( ($country_id && $country_id != 0) && ($state_id && $state_id != 0) ) {
            $tax_row = Tax::where('state_id', $state_id)
                ->where('country_id', $country_id)
                ->where('country_wide', "0")
                ->first();

        }

        // check if tax row is still null and user has country selected.
        if (is_null($tax_row) && $country_id) {
            $tax_row = Tax::where('country_id', $country_id)
                    ->where('country_wide', "1")
                    ->first();

        }

        // check if admin has not setup the tax for
        // the current user country and state.
        if (!is_null($tax_row)) {
            $tax_rate = $tax_row->rate;
        } else {
            $tax_rate = setting('cart.tax', 0);
        }

        config()->set('cart.tax', $tax_rate);

        if ($this->countItems()) {
            foreach ( parent::content() as $row ) {
                parent::setTax($row->rowId, $tax_rate);
            }
        }
    }

    /**
     * @param Courier $courier
     * @return mixed
     */
    public function getShippingFee(Courier $courier)
    {
        return number_format($courier->cost, 2);
    }

    /**
     * Clear the cart content
     */
    public function clearCart()
    {
        session()->forget('discounted');
        session()->forget('discount_amount');
        session()->forget('coupon_code');
        session()->forget('coupon_id');
        session()->forget('on_subtotal');

        parent::destroy();
    }

    /**
     * @param Customer $customer
     * @param string $instance
     */
    public function saveCart(Customer $customer, $instance = 'default')
    {
        parent::instance($instance)->store($customer->email);
    }

    /**
     * @param Customer $customer
     * @param string $instance
     * @return Cart
     */
    public function openCart(Customer $customer, $instance = 'default')
    {
        parent::instance($instance)->restore($customer->email);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCartItemsTransformed() : Collection
    {
        return $this->getCartItems()->map(function ($item) {
            if ($item->options['type'] === 'plan') {
                $item->associate(Plan::class);
            } elseif ($item->options['type'] === 'product') {
                $item->associate(Product::class);
            } elseif (($item->options['type'] === 'addon')) {
                $item->associate(Addon::class);
            }
            return $item;
        });
    }

    /**
     * @param $coupon
     */
    public function applyCouponCode($coupon)
    {
        if ($coupon->type == 1) {
            $cart_amount = $coupon->on_subtotal == 1 ? $this->subtotal() : $this->total();

            $discountAmount = ($cart_amount / 100) * $coupon->amount;
        } else {
            $discountAmount = $coupon->amount;
        }

        session()->put(['discounted' => true]);
        session()->put(['discount_amount' => $discountAmount]);
        session()->put(['coupon_code' => $coupon->code]);
        session()->put(['coupon_id' => $coupon->id]);
        session()->put(['on_subtotal' => $coupon->on_subtotal]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Session\SessionManager|\Illuminate\Session\Store|int|mixed
     */
    public function discountFloat()
    {
        if (session('discounted')) {
            return session('discount_amount');
        }

        return 0;
    }

    public function destroy()
    {
        session()->forget('discounted');
        session()->forget('discount_amount');
        session()->forget('coupon_code');
        session()->forget('coupon_id');
        session()->forget('on_subtotal');

        parent::destroy();
    }
}
