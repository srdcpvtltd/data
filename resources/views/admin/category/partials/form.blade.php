<h4 class="c-grey-900 mT-10 mB-30">Category Information</h4>

<div class="bgc-white bd bdrs-3 p-20 mB-20">

    @include('admin.layouts.errors')

    <div class="mb-3">
        {!! Form::label('name', 'Category name:', ['class' => 'mb-2']) !!}
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Category name here']) !!}
    </div>

    <div class="mb-3">
        {!! Form::label('description', 'Description:', ['class' => 'mb-2']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
    </div>

    <div class="mb-3">
        {!! Form::label('term_list', ' Parent:', ['class' => 'mb-2']) !!}
        {!! Form::select('parent', $categoryArray, null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::input('submit', 'submit', 'Save Category', ['class' => 'btn btn-primary']) !!}
        @if ( Request::is('*/edit') )
            <a href="{{route('ch-admin.category.index')}}" class="btn btn-default">Cancel</a>
        @endif
    </div>
</div>

