<?php

namespace App\Http\Livewire\Admin\Product;

use App\Models\Plan;
use App\Models\Product;
use Livewire\Component;

class AddPlan extends Component
{
    public Product $product;

    public ?Plan $editingPlan;

    public ?string $name = null;

    public ?string $description = null;

    public string $plan_price = '';

    public ?string $features = null;

    protected array $rules = [
        'plan_price' => 'required',
    ];

    protected $listeners = ['refreshListing' => '$refresh'];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingPlan = new Plan();
        $this->name = null;
        $this->description = null;
        $this->plan_price = '';
        $this->features = null;
    }

    public function createOrUpdate($id): void
    {
        $this->validate();

        $planData = [
            'product_id' => $id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->plan_price,
            'features' => json_encode(explode("\n", $this->features)),
        ];

        if ($this->editingPlan->exists) {
            $this->editingPlan->update($planData);
        } else {
            Plan::create($planData);
        }

        $this->resetForm();

        $this->dispatchBrowserEvent('planAdded');
        $this->reset(['name', 'description', 'plan_price', 'features']);
        $this->emit('refreshListing');
    }

    public function openEditModal($planId): void
    {
        $this->editingPlan = Plan::find($planId);
        $this->name = $this->editingPlan->name;
        $this->description = $this->editingPlan->description;
        $this->plan_price = $this->editingPlan->price;
        $this->features = implode("\n", $this->editingPlan->features);

        // Open the modal with the clicked plan
        $this->dispatchBrowserEvent('openEditModal');
    }

    public function delete($id): void
    {
        Plan::find($id)->delete();
        $this->emit('refreshListing');
    }

    public function render()
    {
        return view('livewire.admin.product.add-plan', [
            'plans' => $this->product->plans
        ]);
    }
}
