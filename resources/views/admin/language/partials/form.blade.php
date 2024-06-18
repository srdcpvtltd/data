<div class="col-md-6">
    <div class="bgc-white bd bdrs-3 p-20 mB-20">
        <h4 class="c-grey-900 mT-10 mB-30">Language Information</h4>
        @include('admin.layouts.errors')

        <div class="mb-3">
            {!! Form::label('name', 'Language name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Language name here']) !!}
        </div>

        @if ( !isset($language) || (isset($language) && $language->locale != 'en') )
            <div class="mb-3">
                {!! Form::label('locale', 'Locale:') !!}
                {!! Form::text('locale', null, ['class' => 'form-control', 'placeholder' => 'i.e. en']) !!}
            </div>
        @endif

        <div class="mb-3">
            {!! Form::label('direction', 'Direction:') !!}
            {!! Form::select('direction', ['ltr' => 'Left to Right', 'rtl' => 'Right to Left'], null, ['class' => 'form-control']) !!}
        </div>

        <div class="mb-3">
            {!! Form::input('submit', 'submit', 'Save Language', ['class' => 'btn btn-primary text-white']) !!}
            @if ( Request::is('*/edit') )
                <a href="{{route('ch-admin.language.index')}}" class="btn btn-default">Cancel</a>
            @endif
        </div>
    </div>
</div>
