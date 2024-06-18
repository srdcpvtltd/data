<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Models\OrderMeta;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

class OrderRepository {

    
    public function __construct(Order $order) 
    {
        $this->model = $order;
    }

    public function create(array $data)
    {
        try {

        } catch (QueryException $e) {
            throw $e;
        }
        
    }
    
    
    public function find($id) 
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw $ex;
        }
    }
    
}
