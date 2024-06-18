<div class="col-md-8">
    <h4 class="c-grey-900 mT-10 mB-30">{{$submitLabel}}</h4>

    @include('admin.layouts.errors')

    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <div class="box-body">
            <div class="mb-3">
                {!! Form::label('first_name', 'First name:', ['class' => 'mb-1']) !!}
                {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First name']) !!}
            </div>

            <div class="mb-3">
                {!! Form::label('last_name', 'Last name:', ['class' => 'mb-1']) !!}
                {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
            </div>

            <div class="mb-3">
                {!! Form::label('email', 'Email:', ['class' => 'mb-1']) !!}
                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            </div>

            @if(!isset($user))
                <div class="mb-3">
                    {!! Form::checkbox('welcome_email', 1, true, ['class' => '', 'id' => 'welcome_email']) !!}
                    {!! Form::label('welcome_email', 'Send Welcome Email') !!}
                </div>
            @endif

            <div class="mb-3">
                {!! Form::label('password', 'Password:', ['class' => 'mb-1']) !!}
                {!! Form::text('password', '', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                {!! Route::currentRouteName() == 'ch-admin.user.edit' ? '<small class="help-block">Leave blank to keep the existing one.</small>' : '' !!}
            </div>

            <div class="mb-3">
                {!! Form::label('roles', ' Role:', ['class' => 'mb-1']) !!}
                {!! Form::select('roles', $roles, 2, ['class' => 'form-control']) !!}
            </div>

            <hr>

            <h3>Billing Details:</h3>

            <div class="mb-3">
                {!! Form::label('usermeta[billing_address]', 'Billing Address (optional):', ['class' => 'mb-1']) !!}
                {!! Form::text('usermeta[billing_address]', null, ['class' => 'form-control', 'placeholder' => 'Billing Address']) !!}
            </div>

            <div class="mb-3">
                {!! Form::label('usermeta[billing_city]', 'Billing City (optional):', ['class' => 'mb-1']) !!}
                {!! Form::text('usermeta[billing_city]', null, ['class' => 'form-control', 'placeholder' => 'City']) !!}
            </div>


            <div class="mb-3">
                {!! Form::label('usermeta[billing_zip]', 'Billing Zip/Postal Code (optional):', ['class' => 'mb-1']) !!}
                {!! Form::text('usermeta[billing_zip]', null, ['class' => 'form-control', 'placeholder' => 'Zip/Postal Code']) !!}
            </div>

            <div class="mb-3">
                {!! Form::label('usermeta[billing_country]', 'Billing Country (optional):', ['class' => 'mb-1']) !!}
                {!! Form::select('usermeta[billing_country]', ['' => 'Please select'] + $countries, null, ['class' => 'form-control user-country']) !!}
            </div>

            <div class="mb-3 user-state">
                {!! Form::label('state', 'State (optional):', ['class' => 'mb-1']) !!}
                <div class="state-container">
                    @if(Route::currentRouteName() === 'ch-admin.service.edit')
                        {!! Form::text('usermeta[billing_state]', null, ['class' => 'form-control', 'placeholder' => 'State']) !!}
                    @else
                        {!! Form::select('usermeta[billing_state]', $states, null, ['class' => 'form-control user-state']) !!}
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::input('submit', 'publish', $submitLabel, ['class' => 'btn btn-primary']) !!}
                <a href="{{route('ch-admin.user.index')}}" class="btn btn-default">Cancel</a>
            </div>


        </div>
    </div>

</div>


<script id="states-template" type="text/x-handlebars-template">
    <select class="form-control" name="usermeta[billing_state]">
        <option value="">Select State/Province</option>
        @{{#each this}}
            <option value="@{{ @key }}">@{{this}}</option>
        @{{/each}}
    </select>
</script>
