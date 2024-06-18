<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;

class UserService
{
    private $auth;

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }
    
    
    public function find($id) 
    {
        return $this->userRepository->find($id);
    }
    
    public function update($data, $id)
    {
        return $this->userRepository->update($data, $id);
    }
    
    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }
}