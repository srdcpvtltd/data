<?php

namespace App\Repositories;

use App\Models\Term;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

class CategoriesRepository {
    
    
    public function __construct(Term $categories) 
    {
        $this->model = $categories;
    }

    public function create(array $data)         
    {
        try {
            $categories = Term::create([
                    'name' => $data['name'],
                    'parent' => $data['parent'] == '' ? NULL : $data['parent'],
                    'description' => $data['description']
            ]);

            return $categories;
            
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
        $categories = $this->find($id);

        try {
            $categories->update($data);
            
            return $categories;
            
        } catch (\Exception $exc) {
            throw $exc;
        }
    }
    
    public function delete($id)
    {
        $categories = $this->find($id);

        try {
            $categories->delete();
            $model = $this->model;
            $this->model->where('parent', $id)->get()->each(function ($category, $key) use ($model) {
                $model->find($category->id)->update(['parent' => 0]);
            });

            return $categories;
        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    public function menu()
    {
        try {
            return $this->model->where('parent',0)->with('childs')->get();

        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    public function all()
    {
        try {
            return $this->model->all();

        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    
}
