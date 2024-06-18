@extends('themes.default.app', ['title' => trans('order.title|Your order was cancelled.')])

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="col-md-8 col-md-offset-2">
                @if(isset($failed))
                    <h1>@lang('order.heading|Your order was failed.')</h1>
                @else
                    <h1>@lang('order.heading|Your order was cancelled.')</h1>
                @endif
                <p class="lead">@lang('order.You can place more order by visiting more services.')</p>
                <p><a class="btn btn-primary" href="{{site_url('/')}}">@lang('order.Browse Services')</a></p>
            </div>
        </div>
    </div>
@endsection
