@extends('themes.default.app', ['title' => trans('order.title|Orders')])

@section('content')
    <div class="container page-ctn">
        <div class="row">

            <div class="col-md-3 col-sm-3">
                @include('themes.default.account.nav')
            </div>

            <div class="col-md-9 col-sm-9">

                <div class="panel panel-default">
                    <h2 class="panel-heading">@lang('order.heading|Orders')</h2>

                    <div class="panel-body">


                        <div class="table-responsive">
                            <table class='table table-hover table-condensed table-bordered'>
                                <thead>
                                <tr>
                                    <th>@lang('order.Order ID')</th>
                                    <th>@lang('order.Item')</th>
                                    <th>@lang('order.Status')</th>
                                    <th>@lang('order.Submitted')</th>
                                    <th>@lang('order.Last Reply')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse ( $orders as $order )
                                    <tr>
                                        <td><a href="{{route('ch_order_view', [$order->id])}}">#{{$order->id}}</a></td>
                                        <td>{!! $order->service !!}</td>
                                        <td class="order-status">{!! $order->status_text !!}</td>
                                        <td title="{{$order->created_at}}">{{$order->created_at->diffForHumans()}}</td>
                                        @if ( $order->status != 'cancelled' )
                                            <td><a href="{{route('ch_order_view', [$order->id])}}">{{ ( count( $order->messages ) > 0 ) ? $order->messages->last()->user->name : trans('order.None (Add reply)')}}</a></td>
                                        @else
                                            <td><a href="{{route('ch_order_view', [$order->id])}}">@lang('order.View details')</a></td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center"><strong>@lang('order.No Orders')</strong></td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>


                        <div class="text-center">
                            {{$orders->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
