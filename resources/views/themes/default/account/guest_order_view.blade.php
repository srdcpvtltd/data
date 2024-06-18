@extends('themes.default.app', ['title' => trans('order.title|Order Details')])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 page-ctn">
                @include('themes.default.account.order_content')
            </div>
        </div>
    </div>
@endsection
