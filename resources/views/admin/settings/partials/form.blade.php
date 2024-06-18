<div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Category Information</h3>
        </div>
        <div class="box-body">

            @include('admin.layouts.errors')

            <div class="form-group">
                {!! Form::label('name', 'Category name:') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Category name here']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Description:') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
            </div>
            


            <div class="form-group">
                {!! Form::input('submit', 'submit', 'Save Category', ['class' => 'btn btn-primary']) !!}
                @if ( Request::is('*/edit') )
                    <a href="{{route('ch-admin.category.index')}}" class="btn btn-default">Cancel</a>
                @endif
            </div>

        </div>
    </div>
</div>