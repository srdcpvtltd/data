<?php
namespace App\Services;

use App\Repositories\AddonRepository;

class AddonService
{
    
    private $AddonRepository;

    public function __construct(AddonRepository $AddonRepository) {
        $this->AddonRepository = $AddonRepository;
    }

    public function create(array $data)
    {
        $addon = $this->AddonRepository->create($data);
        
        return $addon;
    }
    
    
    public function find($id) 
    {
        return $this->AddonRepository->find($id);
    }
    
    public function update($data, $id)
    {
        return $this->AddonRepository->update($data, $id);
    }
    
    public function delete($id)
    {
        return $this->AddonRepository->delete($id);
    }
}