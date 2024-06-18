<?php
namespace App\Services;

use App\Repositories\FormRepository;

class FormService
{
    
    private $formRepository;

    public function __construct(FormRepository $formRepository) {
        $this->formRepository = $formRepository;
    }

    public function create(array $data)
    {
        $form = $this->formRepository->create($data);
        
        return $form;
    }
    
    
    public function find($id) 
    {
        return $this->formRepository->find($id);
    }
    
    public function update($data, $id)
    {
        return $this->formRepository->update($data, $id);
    }
    
    public function delete($id)
    {
        return $this->formRepository->delete($id);
    }

    public function all()
    {
        return $this->formRepository->all();
    }
}