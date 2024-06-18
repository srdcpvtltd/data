@extends('themes.default.app', ['title' => trans('order.title|Thank you')])

@section('content')

    <div class="container page-ctn">
        <div class="row text-center">
            <div class="col-6 offset-3">
                <div style="padding: 130px 0">
                    <h1>@lang('order.heading|Thank you')</h1>
                    <p class="lead">@lang('order.We will get started on your order right away. You should be receiving an order confirmation email shortly.')</p>
                    <p><a class="btn btn-primary" href="{{route('ch_order_view', [$order->id, 'key' => $order_key])}}">@lang('order.View Order')</a></p>
                </div>
            </div>
        </div>
    </div>

@endsection
