@extends('admin.layouts.app')

@section('title', $title)

@section('content')

<div class="row">


    {!! Form::open(['url' => 'ch-admin/category', 'class' => 'product-form col-md-6']) !!}

        @include('admin.category.partials.form')

    {!! Form::close() !!}


    @include('admin.category.listing')

</div>

@endsection
