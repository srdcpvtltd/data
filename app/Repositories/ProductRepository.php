<?php

namespace App\Repositories;


use App\Models\ProductMeta;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Addon;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Products\ProductNotFoundException;
use App\Exceptions\Products\CreateProductInvalidArgumentException;
use \Illuminate\Database\QueryException;

class ProductRepository
{
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function createOrUpdate(array $data, $update = false, $id = null)
    {
        try {
            $product = $update == true ? $this->model->find($id) : new $this->model;
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->price = $data['price'] ?? null;
            $product->features = $data['features'] ?? null;
            $product->dynamic_pricing = $data['dynamic_pricing'] ?? false;
            $product->type = $data['type'] ?? 'simple';
            $product->user_id = $data['user_id'];
            $product->status = (isset($data['save'])) ? 0 : 1;

            if ($update == true) {
                $product->update();
            } else {
                $product->save();
            }

            if (isset($data['attachmentIds'])) {
                $product->syncMedia($data['attachmentIds'], 'attachments');
            }

            if (isset($data['plans'])) {
                for ($x = 0; $x < count($data['plans']['price']); $x++) {
                    $product->plans()->create([
                        'name' => $data['plans']['name'][$x] ?? null,
                        'description' => $data['plans']['description'][$x] ?? null,
                        'price' => $data['plans']['price'][$x] ?? null,
                        'features' => json_encode(explode('\n', $data['plans']['features'][$x])) ?? null,
                    ]);
                }
            }

            if (isset($data['term_list']) && !empty($data['term_list'])) {

                $selected_cats = $data['term_list'];

                $terms = [];

                foreach ($selected_cats as $cat) {
                    $terms = array_merge($terms, get_term_parent_ids($cat));
                }

                $product->terms()->sync(array_unique($terms));
            }

            if (isset($data['meta']) && !empty($data['meta'])) {
                foreach ($data['meta'] as $key => $value) {
                    if ($key == 'attachments') {
                        $product->syncMedia($value, 'attachments');
                    } else {
                        $value = is_array($value) ? serialize($value) : $value;
                        $product->meta()->updateOrCreate(['key' => $key], ['value' => $value]);
                    }
                }
            }

            return $product;

        } catch (QueryException $e) {
            throw new CreateProductInvalidArgumentException($e->getMessage(), 500, $e);
        }


    }

    public function find($id)
    {
        try {
            return $this->model::with(['meta'])->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new ProductNotFoundException($ex);
        }
    }

    public function delete($id)
    {
        $product = $this->find($id);

        $product->meta()->delete();

        $product->terms()->sync([]);

        $product->addons()->delete();

        $product->delete();

        return true;
    }

    public function all()
    {
        return $this->model->all();
    }

}
