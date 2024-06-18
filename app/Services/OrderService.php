<?php
namespace App\Services;

use App\Notifications\User\UserCreated;
use App\Repositories\OrderRepository;

class OrderService
{
    private $auth;

    private $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function create(array $data)
    {
        $user = $this->orderRepository->create($data);

        $user->notify(new UserCreated($user));
        
        return $user;
    }
}