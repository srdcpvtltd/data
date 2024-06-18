<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Form;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

class FormRepository {
    
    
    public function __construct(Form $form)
    {
        $this->model = $form;
    }

    public function create(array $data)         
    {
        try {
            $form = Form::create([
                    'name' => $data['name'],
                    'content' => $data['content'],
                    'raw_content' => $data['raw_content']
            ]);

            return $form;
            
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
    
    public function update(array $data, $id) 
    {
        $form = $this->find($id);

        try {
            $form->update($data);
            
            return $form;
            
        } catch (\Exception $exc) {
            throw $exc;
        }
    }
    
    public function delete($id)
    {
        $form = $this->find($id);

        try {
            
            $form->delete();

            return true;
            
        } catch (\Exception $exc) {
            throw $exc;
        }
    }


    public function all()
    {
        return $this->model->all();
    }

    
}
