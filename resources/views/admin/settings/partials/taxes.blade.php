<div class="mb-3 row">
    <label for="settings[taxes][enabled]" class="col-sm-2 control-label">Enable Taxes</label>
    <div class="col-sm-3">
        <select class="form-control" id="settings[taxes][enabled]" name="settings[taxes][enabled]">
            <option value="no" @if ( old('settings.taxes.enabled', setting('taxes.enabled')) == 'no' ) SELECTED @endif>No</option>
            <option value="yes" @if ( old('settings.taxes.enabled', setting('taxes.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
        </select>
    </div>
</div>


<div class="mb-3 row">
    <label for="settings[taxes][enabled]" class="col-sm-2 control-label">Tax Rates</label>
    <div class="col-sm-10">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tax Rates</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Add tax rates for specific Country/State. Enter a percentage i.e: 17.5 for 17.5%.
                </div>
                <div class="addon-wrapper">
                    @if ( $taxes->count() > 0 )

                        @foreach ( $taxes as $tax )

                            <div class="toclone tax-row row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="hidden" id="settings[tax_rates][id][]" name="settings[tax_rates][id][]" value="{{$tax->id}}">
                                        <label for="tax_country" class="control-label">Country</label>
                                        <select class="form-control tax-country" name="settings[tax_rates][country][]">
                                            <option value="">Select Country</option>
                                            @if ( !empty($countries) )
                                                @foreach( $countries as $id => $country )
                                                    <option value="{{ $id }}"{{ $tax->country_id == $id ? " SELECTED" : "" }}>{{ $country }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="settings[tax_rates][state][]" class="control-label">State</label>
                                        <div class="state-container">
                                            <select class="form-control" name="settings[tax_rates][state][]">
                                                <option value="">Select State/Province</option>
                                                @if ( !empty($tax->country->states) && isset($tax->country->states))
                                                    @foreach ($tax->country->states as $state)
                                                    <option value="{{$state->id}}"{{$tax->state_id == $state->id ? "SELECTED" : ""}}>{{$state->name}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings[tax_rates][country_wide][]" class="control-label">Country wide</label>

                                        <select class="form-control" id="settings[tax_rates][country_wide][]" name="settings[tax_rates][country_wide][]">
                                            <option value="0" {{ $tax->country_wide == '0' ? 'SELECTED' : "" }}>No</option>
                                            <option value="1" {{ $tax->country_wide == '1' ? 'SELECTED' : "" }}>Yes</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="settings[tax_rates][rate][]" class="control-label">Rate</label>
                                        <input type="number" step="0.01" class="form-control" min="0.0" id="settings[tax_rates][rate][]" name="settings[tax_rates][rate][]" value="{{$tax->rate}}">
                                    </div>
                                </div>
                                <div class="col-md-1 clone"><a type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i></a></div>
                                <div class="col-md-1 delete"><a type="button" class="delete btn btn-xs btn-danger"><i class="fa fa-minus"></i></a></div>
                            </div>


                        @endforeach

                    @else


                        <div class="toclone tax-row row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="hidden" id="settings[tax_rates][id][]" name="settings[tax_rates][id][]" value="">
                                    <label for="tax_country" class="control-label">Country</label>
                                    <select class="form-control tax-country" name="settings[tax_rates][country][]">
                                        <option value="">Select Country</option>
                                        @if ( !empty($countries) )
                                            @foreach( $countries as $id => $country )
                                            <option value="{{ $id }}">{{ $country }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="settings[tax_rates][state][]" class="control-label">State</label>
                                    <div class="state-container">
                                        <input type="text" class="form-control" id="settings[tax_rates][state][]" name="settings[tax_rates][state][]"
                                           value="">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="settings[tax_rates][country_wide][]" class="control-label">Country wide</label>

                                    <select class="form-control" id="settings[tax_rates][country_wide][]" name="settings[tax_rates][country_wide][]">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="settings[tax_rates][rate][]" class="control-label">Rate</label>
                                    <input type="number" step="0.01" class="form-control" min="0.0" id="settings[tax_rates][rate][]" name="settings[tax_rates][rate][]" value="">
                                </div>
                            </div>
                            <div class="col-md-1 clone"><a type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i></a></div>
                            <div class="col-md-1 delete"><a type="button" class="delete btn btn-xs btn-danger"><i class="fa fa-minus"></i></a></div>
                        </div>

                    @endif

                </div>
            </div>
        </div>


    </div>
</div>



<div class="mb-3 row">
    <label for="settings[cart][tax]" class="col-sm-2 control-label">Fallback Tax Rate</label>
    <div class="col-sm-4">
        <input type="number" step="0.01" min="0.0" class="form-control" id="settings[cart][tax]" name="settings[cart][tax]"
               value="{{ old('settings.cart.tax', setting('cart.tax')) }}" placeholder="17.5">
        <span class="help-block">Customers not in a specific rate will be charged this tax rate. Enter a percentage, such as 17.5 for 17.5%.</span>
    </div>
</div>


<script id="states-template" type="text/x-handlebars-template">
    <select class="form-control" name="settings[tax_rates][state][]">
        <option value="">Select State/Province</option>
        @{{#each this}}
            <option value="@{{ @key }}">@{{this}}</option>
        @{{/each}}
    </select>
</script>
