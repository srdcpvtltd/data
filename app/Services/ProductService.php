<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Events\Dispatcher;

class ProductService extends ProductRepository
{
    /**
     * @param array $data
     * @return mixed
     * @throws \App\Exceptions\Products\CreateProductInvalidArgumentException
     */
    public function create(array $data)
    {
        return parent::createOrUpdate($data);
    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     * @throws \App\Exceptions\Products\CreateProductInvalidArgumentException
     */
    public function update($data, $id)
    {
        return parent::createOrUpdate($data, true, $id);
    }
}