@extends('admin.layouts.app')

@section('title', $title)

@section('content')

<div class="row">

    {!! Form::model( $coupon, [ 'method' => 'PATCH', 'route' => [ 'ch-admin.coupon.update', $coupon->id ], 'class' => 'form' ] ) !!}

        @include('admin.coupon.partials.form', ['submitLabel' => 'Update Coupon'])

    {!! Form::close() !!}

</div>

@endsection
