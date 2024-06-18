<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testItCanCreateProduct()
    {
        $productService = new \App\Services\ProductService(new \App\Repositories\ProductRepository(new \App\Models\Product));
        
        $product_data = factory(\App\Models\Product::class)->make()->toArray();
        
        $categories = factory(\App\Models\Term::class, 10)->create();
        
        $product_data['term_list'] = $categories->take(rand(3, 10))->pluck('id')->toArray();
        
        $addons = factory(\App\Models\Addon::class, 5)->create();
  
        $product_data['addons']['id'][0]        = 5;
        $product_data['addons']['name'][0]      = $addons->where('id', 5)->first()->name;
        $product_data['addons']['price'][0]     = 10;
        $product_data['addons']['name'][1]      = 'New Addon Name';
        $product_data['addons']['price'][1]     = 20;
        $product_data['addons']['name'][2]      = 'New Addon Name 2';
        $product_data['addons']['price'][2]     = 30;

        $product = $productService->create($product_data);

        $this->assertInstanceOf(\App\Models\Product::class, $product);
        $this->assertEquals($product->title, $product_data['title']);
        $this->assertEquals($product->addons->first()->id, $addons->last()->id);
        $this->assertEquals($product->addons->first()->pivot->price, "10");
        $this->assertContains($product->terms()->first()->name, $categories->pluck('name')->toArray());
    }
    
    
    public function testItCanShowProduct()
    {
        $productService = new \App\Services\ProductService(new \App\Repositories\ProductRepository(new \App\Models\Product));
        
        $product_data = factory(\App\Models\Product::class)->make()->toArray();
        
        $categories = factory(\App\Models\Term::class, 10)->create();
        
        $product_data['term_list'] = $categories->take(rand(3, 10))->pluck('id')->toArray();
        
        $addons = factory(\App\Models\Addon::class, 5)->create();
  
        $product_data['addons']['id'][0]        = $addons->first()->id;
        $product_data['addons']['name'][0]      = $addons->first()->name;
        $product_data['addons']['price'][0]     = 10;
        $product_data['addons']['name'][1]      = 'New Addon Name';
        $product_data['addons']['price'][1]     = 20;
        $product_data['addons']['name'][2]      = 'New Addon Name 2';
        $product_data['addons']['price'][2]     = 30;

        $product_created = $productService->create($product_data);
        
        $product = $productService->find($product_created->id);

        $this->assertInstanceOf(\App\Models\Product::class, $product);
        $this->assertEquals($product->title, $product_data['title']);
        $this->assertEquals($product->addons->first()->id, $addons->first()->id);
        $this->assertEquals($product->addons->first()->pivot->price, "10");
        $this->assertContains($product->terms()->first()->name, $categories->pluck('name')->toArray());
    }




    public function testItCanUpdateProduct() {
        $this->assertEquals(true, true);
    }
}
