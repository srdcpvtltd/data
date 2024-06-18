<?php
namespace App\Services;

use App\Repositories\CategoriesRepository;

class CategoriesService
{
    
    private $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository) {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function create(array $data)
    {
        $categories = $this->categoriesRepository->create($data);
        
        return $categories;
    }
    
    
    public function find($id) 
    {
        return $this->categoriesRepository->find($id);
    }
    
    public function update($data, $id)
    {
        return $this->categoriesRepository->update($data, $id);
    }
    
    public function delete($id)
    {
        return $this->categoriesRepository->delete($id);
    }

    public function menu()
    {
        return $this->categoriesRepository->menu();
    }

    public function all()
    {
        return $this->categoriesRepository->all();
    }
}