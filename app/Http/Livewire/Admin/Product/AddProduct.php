<?php

namespace App\Http\Livewire\Admin\Product;

use App\Models\Media;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AddProduct extends Component
{
    public Product $product;

    public bool $dynamic_pricing = false;

    public bool $downloadable = false;

    public ?string $name = '';

    public ?string $description = '';

    public string $type = 'simple';

    public ?string $price = null;

    public ?string $features = null;

    public array $term_list = [];

    public $attachments;

    public $gallery;

    public $categories = [];

    public $meta = [];

    public string $submitLabel = 'Create Product';

    public bool $isProductEdit;

    protected $listeners = [
        'mediaDeleted' => 'refreshMedia',
        'fileUploaded' => 'refreshMedia',
    ];

    public function mount(Product $product): void
    {
        $this->isProductEdit = Route::currentRouteName() === 'ch-admin.product.edit';

        if ($this->isProductEdit) {
            $this->product = $product;

            $this->submitLabel = 'Update Product';

            $this->fill([
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'features' => implode("\n", $product->features),
                'term_list' => $product->terms->pluck('id')->all(),
                'meta' => $product->ProductMeta,
                'attachments' => $product->getMedia('attachments'),
                'gallery' => $product->getMedia('gallery'),
                'downloadable' => $product->hasMedia('attachments') ? 1 : 0,
                'dynamic_pricing' => $product->dynamic_pricing
            ]);
        } else {
            $this->submitLabel = 'Add Pricing & Addons <i class="ti-arrow-circle-right"></i>';
        }
    }

    public function submitForm()
    {
        $productService = new ProductService(new Product());

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'dynamic_pricing' => $this->dynamic_pricing,
            'price' => $this->price,
            'features' => $this->features ? json_encode(explode("\n", $this->features)) : null,
            'term_list' => $this->term_list,
            'meta' => $this->meta,
            'user_id' => auth()->user()->getAuthIdentifier()
        ];

        if (!$this->isProductEdit) {
            $product = $productService->create($data);
        } else {

            $validator = Validator::make($data, [
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => [
                    'required_if:dynamic_pricing,false',
                    'nullable',
                    'numeric',
                    'min:0',
                    Rule::requiredIf(function () {
                        return $this->dynamic_pricing && $this->product->plans()->count() === 0;
                    }),
                ],
                'features' => [
                    'required_if:dynamic_pricing,false',
                ]
            ], [
                'price.required_if' => 'The price field is required when dynamic pricing is disabled.',
                'price.required' => 'The product must have at least one active plan when dynamic pricing is enabled.',
            ]);

            if ($validator->fails()) {
                $this->dispatchBrowserEvent('validationFailed');
                $validator->validate();
            }



            $product = $productService->update($data, $this->product->id);
        }

        return redirect()->route('ch-admin.product.edit', [$product->id]);
    }

    public function toggleDynamicPricing(): void
    {
        $this->dynamic_pricing = !$this->dynamic_pricing;
    }

    public function toggleDownloadable(): void
    {
        $this->downloadable = !$this->downloadable;

        if ($this->downloadable) {
            $this->dispatchBrowserEvent('enableAttachmentUploader');
        }

    }

    public function deleteMedia($id): void
    {
        $media = Media::findOrFail($id);
        if ($media->media_meta) {
            foreach ($media->media_meta['sizes'] as $key => $value) {
                unlink($value['path']);
            }
        }

        $this->product->detachMedia($media);

        $media->delete();

        $this->emit('mediaDeleted');
    }

    public function refreshMedia(): void
    {
        $this->attachments = $this->product->getMedia('attachments');
        $this->gallery = $this->product->getMedia('gallery');
    }

    public function render()
    {
        return view('livewire.admin.product.add-product');
    }
}
