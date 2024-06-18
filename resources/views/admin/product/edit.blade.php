@extends('admin.layouts.app')

@section('title', $title)

@section('content')

    <div class="row">
        @include('admin.product.partials.form', ['submitLabel' => 'Add Features & Pricing <i class="ti-arrow-circle-right"></i>'])
    </div>

@endsection
