<div>
    <div class="bgc-white p-20 mb-20 bd">
        <button type="button" class="btn btn-primary btn-sm text-white float-end addon-field" data-bs-toggle="modal" data-bs-target="#addonModal" id="add-plans"><i class="ti-plus"></i> Add Addon</button>
        <h6 class="c-grey-900">Addons</h6>
        <div class="mT-30">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Addon name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($addons as $addon)
                            <tr>
                                <th scope="row">{{$addon->id}}</th>
                                <td>{{$addon->name}}</td>
                                <td>{{$addon->description}}</td>
                                <td>{!! ch_format_price($addon->price) !!}</td>
                                <td>
                                    <div class="btn-group float-end" role="group">
                                        <button type="button" wire:click="openEditModal({{$addon->id}})" class="btn btn-sm text-info bgc-white bdrs-2 mR-3 cur-p">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" wire:click="delete({{$addon->id}})" class="btn btn-sm text-danger bgc-white bdrs-2 mR-3 cur-p">
                                            <i class="ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div wire:ignore.self class="modal fade addon-modal" id="addonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addonModalLabel">Add Product Addons</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" wire:model.defer="name" class="form-control" placeholder="Addon name i.e. Fast Delivery">
                            @error('name')
                            <div class="text-danger text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">$</span>
                                <input type="text" wire:model.defer="price" id="price" class="form-control text-left" placeholder="0.00">
                            </div>
                            @error('price')
                            <div class="text-danger text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <input type="text" wire:model.defer="description" class="form-control" placeholder="Description (optional)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="resetForm()" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary text-white" wire:click="createOrUpdate({{$product->id}})">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
