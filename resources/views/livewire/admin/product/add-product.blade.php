<div>
    <div class="row">
        <div class="col-md-8">

            @include('admin.layouts.errors')

            <div class="bgc-white p-20 mb-20 bd">
                <h6 class="c-grey-900">General Information</h6>
                <div class="mT-30">

                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" id="name" name="name" wire:model.defer="name" class="form-control" placeholder="Enter product name here">
                    </div>

                    <div class="mb-3" wire:ignore>
                        <label class="form-label" for="description">Description</label>
                        <textarea name="description" wire:model.defer="description" id="description" class="form-control"></textarea>
                    </div>

                    @if($isProductEdit)
                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="dynamic_pricing" wire:change="toggleDynamicPricing()" value="1" @if($dynamic_pricing) checked @endif>
                                        <label class="form-check-label" for="dynamic_pricing">Dynamic Pricing</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="downloadable" value="1" wire:change="toggleDownloadable" @if($downloadable) checked @endif>
                                        <label class="form-check-label" for="downloadable">Downloadable Product</label>
                                    </div>
                                </div>

                                @if(!$dynamic_pricing)
                                    <div class="col-md-4">
                                        <label class="form-label" for="price">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="price">$</span>
                                            <input type="text" name="price" id="price" class="form-control text-left" placeholder="0.00" wire:model="price" wire:key="price-field">
                                        </div>
                                    </div>


                                <div class="col-12 mt-3" wire:ignore>
                                    <div class="mb-3">
                                        <label class="form-label" for="features">Features</label>
                                        <textarea id="features" wire:model.defer="features" rows="4" name="features" class="form-control" placeholder="Feature 1
Feature 2
Feature 3
"></textarea>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($downloadable)
                <div class="bgc-white p-20 mb-20 bd uploader attachments">
                    <button type="button" class="btn btn-primary btn-sm text-white float-end dropzone"
                            id="attachments-uploader"
                            data-media-config='{"previewsContainer": ".product-downloads", "tag": "attachments", "disk": "local", "previewTemplate": "#attachmentsPreviewTemplate"}'
                            @if(isset($product)) data-product_id="{{$product->id}}" @endif>
                        <i class="ti-plus"></i> Upload Attachments
                    </button>

                    <h6 class="c-grey-900">Product Downloads</h6>

                    <div class="mT-30 form-group">
                        <div class="product-downloads">
                            @if($product->hasMedia('attachments'))
                                @foreach($product->getMedia('attachments') as $attachment)
                                    <div class="attachment-row d-inline-block dz-processing dz-complete">
                                        <div class="dz-file-info">
                                            <p>
                                                <a class="btn btn-xs" wire:click="deleteMedia({{$attachment->id}})">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                                <i class="fa fa-paperclip"></i>
                                                <span class="name mb-0">{{$attachment->filename . '.' . $attachment->extension}}</span>
                                                <strong class="size mb-0" data-dz-size="">{{$attachment->readableSize()}}</strong>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($isProductEdit)

                @if($dynamic_pricing)
                    @livewire('admin.product.add-plan', ['product' => $product], key(uniqid()))
                @endif

                @livewire('admin.product.add-addon', ['product' => $product], key(uniqid()))

                <div class="bgc-white p-20 mb-20 bd uploader gallery">

                    <button type="button" class="btn btn-primary btn-sm text-white float-end dropzone"
                            id="gallery-uploader"
                            data-media-config='{"previewsContainer": ".service-gallery-images", "tag": "gallery"}'
                            @if(isset($product)) data-product_id="{{$product->id}}" @endif>
                        <i class="ti-plus"></i> Upload Images
                    </button>

                    <h6 class="c-grey-900">Product Gallery</h6>

                    <div class="mT-30 form-group">
                        <div class="service-gallery-images">
                            @if($product->hasMedia('gallery'))
                                @foreach($gallery as $media)
                                    <div class="row dz-complete dz-success dz-image-preview">
                                        <div class="col-md-12">
                                            <div class="file-row clearfix">
                                                <div class="dz-actions pull-right">
                                                    <a class="btn btn-sm btn-danger delete" wire:click="deleteMedia({{$media->id}})" wire:loading.class="disabled" wire:target="deleteMedia({{$media->id}})">
                                                        <i class="fa fa-trash"></i> <span>Delete</span>
                                                    </a>
                                                </div>
                                                <div class="dz-preview-file">
                                                    <span class="preview">
                                                        <img alt="{{$media->filename . '.' . $media->extension}}" src="{{$media->getUrl('thumbnail')}}">
                                                    </span>
                                                </div>
                                                <div class="dz-file-info">
                                                    <p class="name mb-0">{{$media->filename . '.' . $media->extension}}</p>
                                                    <p class="size mb-0">{{$media->readableSize()}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bgc-white p-20 mb-20 bd">
                    <h6 class="c-grey-900">SEO Settings (optional)</h6>
                    <div class="mT-30">
                        <div class="mb-3">
                            <label class="form-label" for="meta[seo_title]">Title</label>
                            {!! Form::text('meta[seo_title]', null, ['class' => 'form-control', 'placeholder' => 'Enter title here', 'wire:model.defer' => 'meta.seo_title']) !!}
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="meta[seo_description]">Description</label>
                            {!! Form::textarea('meta[seo_description]', null, ['class' => 'form-control', 'rows' => 3, 'wire:model.defer' => 'meta.seo_description']) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="bgc-white p-20 mb-20 bd">
                <h6 class="c-grey-900">Categories</h6>
                <div class="form-group mb-3">
                    {!! Form::select('term_list[]', $categories, null, ['class' => 'form-control', 'multiple', 'wire:model.defer' => 'term_list']) !!}
                </div>

                <div>
                    <button type="submit" class="btn btn-primary text-white" wire:click="submitForm">{!! $submitLabel !!}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
    <script src="{{url('assets/backend/js/vendors/ckeditor.js')}}"></script>
@endpush

@push('scripts')
    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script>Dropzone.autoDiscover = false;</script>
    <script src="{{ url('assets/backend/js/media.js') }}" defer></script>
    <script type="text/x-handlebars-template" id="attachmentsPreviewTemplate">

        <div class="attachment-row d-inline-block">
            <div class="dz-file-info">
                <p><a data-dz-remove class="btn btn-sm btn-danger cancel">
                        <i class="fa fa-stop-circle" title="Stop"></i>
                    </a> <a data-dz-remove class="btn btn-xs">
                        <i class="fa fa-trash text-danger"></i>
                    </a> <i class="fa fa-paperclip"></i> <span class="name mb-0" data-dz-name></span> <span class="size mb-0" data-dz-size></span></p>
                <strong class="error text-danger" data-dz-errormessage></strong>
            </div>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                 aria-valuenow="0">
                <div class="progress-bar progress-bar-success" style="width:100%;" data-dz-uploadprogress></div>
            </div>
        </div>

    </script>
    @include('admin.media.upload')
    <script>
        function initializeCKEditor() {
            ClassicEditor.create(document.querySelector('#description'))
                .then(editor => {
                    editor.setData(`{!! $description !!}`);

                    editor.model.document.on('change:data', () => {
                    @this.set('description', editor.getData());
                    });
                }).catch(error => {
                console.error(error);
            });
        }

        document.addEventListener('livewire:load', function () {
            initializeCKEditor();
        });

        // Re-initialize CKEditor after Livewire re-render
        document.addEventListener('validationFailed', function (e) {
            setTimeout(() => {
                initializeCKEditor();
            }, 300);
        });

        document.addEventListener('enableAttachmentUploader', function (event) {
            initializeDropzone(document.querySelector('#attachments-uploader'));
        });

        window.addEventListener("fileUploaded", function (e) {
            Livewire.emit('fileUploaded');
        });

        window.addEventListener('planAdded', event => {
            $('.entry-modal').modal('hide');
        });

        window.addEventListener('openEditModal', event => {
            $('.entry-modal').modal('show');
        });

        window.addEventListener('addonAdded', event => {
            $('.addon-modal').modal('hide');
        });

        window.addEventListener('openEditAddonModal', event => {
            $('.addon-modal').modal('show');
        });

    </script>
@endpush
