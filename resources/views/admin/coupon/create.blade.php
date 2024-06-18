@extends('admin.layouts.app')

@section('title', $title)

@section('content')

<div class="row">

    {!! Form::open(['url' => 'ch-admin/coupon', 'class' => 'form']) !!}

    @include('admin.coupon.partials.form', ['submitLabel' => 'Create Coupon'])

    {!! Form::close() !!}

</div>

@endsection
