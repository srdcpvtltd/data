<?php

namespace App\Repositories;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CouponRepository extends BaseRepository
{
    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    /**
     * Create a coupon.
     *
     * @param array $data
     * @param array $products
     * @return mixed
     */
    public function create(array $data, array $products)
    {
        return DB::transaction(function () use ($data, $products) {
            $coupon = $this->model::create($data);

            if ($coupon) {

                if (isset($products['products']) && sizeof($products) > 0) {
                    $coupon->products()->sync($products['products']);
                }

                return $coupon;
            }

            throw new \Exception('Unable to create Coupon code');
        });
    }

    /**
     * Update a coupon.
     *
     * @param array $data
     * @param array $products
     * @param $id
     * @return mixed
     */
    public function update(array $data, array $products, $id)
    {
        $coupon = $this->getById($id);

        return DB::transaction(function () use ($coupon, $data, $products) {
            $updated = $coupon->update($data);

            $products = isset($products['products']) ? $products['products'] : [];

            $coupon->products()->sync($products);

            if ($updated) {
                return true;
            }

            throw new \Exception('Unable to update Coupon code');
        });
    }

    /**
     * Delete a coupon.
     *
     * @param $id
     * @return bool|void|null
     * @throws \Exception
     */
    public function deleteById($id)
    {
        $coupon = $this->getById($id);

        $coupon->products()->sync([]);

        $coupon->delete();
    }

    /**
     * @param $code
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByCode($code)
    {
        return $this->getByColumn($code,'code');
    }
}