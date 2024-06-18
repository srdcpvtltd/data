@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <div class="col-md-12">
        <h4 class="c-grey-900 mT-10 mB-30">{{$title}}</h4>
    </div>

    @livewire('admin.product.add-product', ['categories' => $categories, $product ?? new App\Models\Product()])
@endsection
