<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Addon;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

/**
 * Description of AddonRepository
 *
 * @author rk
 */
class AddonRepository {
    
    
    public function __construct(Addon $addon) 
    {
        $this->model = $addon;
    }

    public function create(array $data)         
    {
        try {
            $addon = Addon::create([
                    'name' => $data['name'],
                    'description' => $data['description']
            ]);

            return $addon;
            
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
        $addon = $this->find($id);

        try {
            $addon->update($data);
            
            return $addon;
            
        } catch (\Exception $exc) {
            throw $exc;
        }
    }
    
    public function delete($id)
    {
        $addon = $this->find($id);

        try {
            
            $addon->delete();

            return $addon;
            
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    
}
