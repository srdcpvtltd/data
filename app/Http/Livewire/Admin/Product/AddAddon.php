<?php

namespace App\Http\Livewire\Admin\Product;

use App\Models\Addon;
use App\Models\Plan;
use App\Models\Product;
use Livewire\Component;

class AddAddon extends Component
{
    public Product $product;

    public ?Addon $editingAddon;

    public string $name = '';

    public ?string $description = null;

    public string $price = '';

    protected array $rules = [
        'name' => 'required',
        'price' => 'required',
    ];

    protected $listeners = ['refreshAddonsListing' => '$refresh'];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingAddon = new Addon();
        $this->name = '';
        $this->description = null;
        $this->price = '';
    }

    public function createOrUpdate($id): void
    {
        $this->validate();

        $addonData = [
            'product_id' => $id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ];

        if ($this->editingAddon->exists) {
            $this->editingAddon->update($addonData);
        } else {
            Addon::create($addonData);
        }

        $this->resetForm();

        $this->dispatchBrowserEvent('addonAdded');
        $this->reset(['name', 'description', 'price']);
        $this->emit('refreshAddonsListing');
    }

    public function openEditModal($planId): void
    {
        $this->editingAddon = Addon::find($planId);
        $this->name = $this->editingAddon->name;
        $this->description = $this->editingAddon->description;
        $this->price = $this->editingAddon->price;

        // Open the modal with the clicked addon
        $this->dispatchBrowserEvent('openEditAddonModal');
    }

    public function delete($id): void
    {
        Addon::find($id)->delete();
        $this->emit('refreshAddonsListing');
    }

    public function render()
    {
        return view('livewire.admin.product.add-addon', [
            'addons' => $this->product->addons
        ]);
    }
}
