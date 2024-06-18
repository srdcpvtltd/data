@extends('admin.layouts.app')

@section('title', $title)

@section('content')

<div class="row">


    {!! Form::model($category, [ 'method' => 'PATCH', 'route' => [ 'ch-admin.category.update', $category->id ] ]) !!}

        @include('admin.category.partials.form')

    {!! Form::close() !!}

</div>

@endsection
