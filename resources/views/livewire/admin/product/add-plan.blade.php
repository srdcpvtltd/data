<div>
    <div class="bgc-white p-20 mb-20 bd">
        <button type="button" class="btn btn-primary btn-sm text-white float-end plan-field" data-bs-toggle="modal" data-bs-target="#entryModal" id="add-plans"><i class="ti-plus"></i> Add Plan</button>
        <h6 class="c-grey-900">Features & Pricing</h6>
        <div class="mT-30">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Plan name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <th scope="row">{{$plan->id}}</th>
                                <td>{{$plan->name}}</td>
                                <td>{{$plan->description}}</td>
                                <td>{!! ch_format_price($plan->price) !!}</td>
                                <td>
                                    <div class="btn-group float-end" role="group">
                                        <button type="button" wire:click="openEditModal({{$plan->id}})" class="btn btn-sm text-info bgc-white bdrs-2 mR-3 cur-p">
                                            <i class="ti-pencil"></i>
                                        </button>
                                        <button type="button" wire:click="delete({{$plan->id}})" class="btn btn-sm text-danger bgc-white bdrs-2 mR-3 cur-p">
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
    <div wire:ignore.self class="modal modal-lg fade entry-modal" id="entryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="entryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Plan/Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 plan-field">
                            <label class="form-label sr-only" for="name">Name</label>
                            <input type="text" wire:model.defer="name" id="name" class="form-control" placeholder="Plan name i.e. Basic" value="">
                        </div>

                        <div class="col-md-4 mb-3 plan-field">
                            <label class="form-label sr-only" for="description">Plan Description (short)</label>
                            <input type="text" wire:model.defer="description" id="description" class="form-control" placeholder="Plan Description (short)" value="">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label sr-only" for="price">Price</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">$</span>
                                <input type="text" wire:model="plan_price" id="price" class="form-control text-left" placeholder="0.00">
                            </div>
                            @error('plan_price') <div class="text-danger text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label sr-only" for="features">Features</label>
                        <textarea id="features" wire:model.defer="features" rows="4" name="features" class="form-control" placeholder="Feature 1
Feature 2
Feature 3
"></textarea>
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
