@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="c-grey-900 mT-10 mB-30">Edit Form</h4>
        </div>
        <div class="col-md-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">

                {!! Form::model( $form, [ 'method' => 'PATCH', 'route' => [ 'ch-admin.form.update', $form->id ], 'class' => 'form' ] ) !!}

                @include('admin.form.partials.form', ['submitLabel' => 'Update'])

                {!! Form::close() !!}

                @include('admin.form.partials.builder')
            </div>
        </div>
    </div>
@endsection
