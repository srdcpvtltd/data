@extends('admin.layouts.master')

@section('title', $title)

@section('content')

<div class="row">


    {!! Form::open(['url' => 'ch-admin/create', 'class' => 'order-form']) !!}

    @include('admin.order.partials.form')

    {!! Form::close() !!}


</div>

@endsection
