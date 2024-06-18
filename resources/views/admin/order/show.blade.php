@extends('admin.layouts.app')


@section('title', $title)

@section('content')

    <form class="form-horizontal" action="{{route('ch-admin.order.update', $order->id)}}" method="post">
        <div class="row">

            {{method_field('PUT')}}
            {{csrf_field()}}
            <div class="col-md-8">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <section class="invoice mb-20">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="c-grey-900 mT-10 mB-30">
                                    <i class="fa fa-cart-plus"></i> Order #{{$order->id}} details - <a href="{{route('ch-admin.order.messages', [$order->id])}}">Messages</a>
                                    <small class="pull-right">Date Time: {{$order->created_at}}</small>
                                </h3>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <p class="lead">General:</p>
                                <strong>Submitted: </strong><span
                                    title="{{$order->created_at}}">{{$order->created_at->diffForHumans()}}</span><br>
                                <strong>Order
                                    Status: </strong>{{ $order->status == 'pending' ? 'Pending Payment' : ucfirst($order->status) }}
                                <br><br>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <p class="lead">Billing Details:</p>
                                <address>
                                    @if($order->user_id == 0)
                                        <strong>Guest Order</strong><br>
                                        <strong>Name:</strong> {{ $order->getMeta('billing_first_name') . ' ' . $order->getMeta('billing_last_name') }}<br>
                                        <strong>Email:</strong> {!! $order->getMeta('billing_email') ?? 'N/A' !!}<br>
                                    @else
                                        <strong>Name:</strong> <a
                                            href="{{ route('ch-admin.user.edit', [$order->user->id]) }}">{{ $order->getMeta('billing_first_name') . ' ' . $order->getMeta('billing_last_name') }}</a><br>
                                        <strong>Address:</strong> {!! $order->getMeta('billing_address') ?? 'N/A' !!}<br>
                                        <strong>City:</strong> {!! $order->getMeta('billing_city') ?? 'N/A' !!}<br>
                                        <strong>State:</strong> {!! $order->state->name ?? 'N/A' !!}<br>
                                        <strong>Country:</strong> {!! $order->country->name ?? 'N/A' !!}<br>
                                        <strong>Zip code:</strong> {!! $order->getMeta('billing_zip') ?? 'N/A' !!}<br>
                                    @endif

                                </address>
                            </div>
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <p class="lead mt-30">Item Purchased:</p>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ( $order->items as $item )
                                        <!-- where('key', '_item_name')->first()->value -->
                                        <tr>
                                            <td>{!! $item->name() !!}</td>
                                            <td>{{ $item->qty() }}</td>
                                            <td>{!! ch_format_price( $item->subtotal(), $order->currency() ) !!}</td>
                                            <td>{!! ch_format_price( $item->total(), $order->currency() ) !!}</td>
                                        </tr>

                                    @endforeach

                                    @if ( $order->addons && count($order->addons) > 0)
                                        @foreach ( $order->addons as $addon )

                                            <tr>
                                                <td>{{ $addon['name'] or "" }}</td>
                                                <td>1</td>
                                                <td>{!! ch_format_price( $addon['price'], $order->currency() ) !!}</td>
                                                <td>{!! ch_format_price( $addon['price'], $order->currency() ) !!}</td>
                                            </tr>

                                        @endforeach
                                    @endif


                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-md-6">

                                <p class="lead">Payment Details:</p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th class="w-50p">Payment method:</th>
                                            <td>{{ ucfirst(str_replace('_', ' ', $order->PaymentMethod())) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <td>{{ $order->transactionId() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <p class="lead">Summary</p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th class="w-50p">Subtotal:</th>
                                            <td>{!! $order->subtotal() !!}</td>
                                        </tr>
                                        @if($order->getMeta('discounted'))
                                            <tr>
                                                <th>Coupon Used</th>
                                                <td>{!! $order->getMeta('coupon_code') !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Coupon Amount</th>
                                                <td>{!! ch_format_price($order->getMeta('discount_amount'), $order->getMeta('_order_currency')) !!}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Tax</th>
                                            <td>{!! $order->taxTotal() !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{!! $order->totalFormatted() !!}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </section>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">

                    <div class="mb-5">

                        <h4 class="c-grey-900 mT-10">Update Order</h4>

                        @if($order->PaymentMethod() == 'offline_payments')
                            <div class="mb-3">
                                <label for="transaction_id" class="col-sm-12 mb-2">Transaction Id</label>
                                <div class="col-sm-12">
                                    <input type="text" name="transaction_id" id="transaction_id" class="form-control"
                                           value="{{$order->transactionId()}}"/>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="status" class="col-sm-12 mb-2">Order Status</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="status">
                                    <option value="pending" {{ $order->status == 'pending' ? 'SELECTED' : '' }}>Pending
                                        Payment
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'SELECTED' : '' }}>
                                        Processing
                                    </option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'SELECTED' : '' }}>
                                        Completed
                                    </option>
                                    <option value="refunded" {{ $order->status == 'refunded' ? 'SELECTED' : '' }}>
                                        Refunded
                                    </option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'SELECTED' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                        </div>

                        <input type="submit" name="update_order" value="Update Order"
                               class="btn btn-block btn-primary">

                    </div>

                    @if($order->customFields->count())
                        <h4 class="c-grey-900 mT-10">Customer Provided Info</h4>
                        <table class="table table-bordered table-striped">
                            <tbody>
                            @foreach($order->customFields as $row)
                                <tr>
                                    <th>{{$row->label}}</th>
                                    <td>{!! $row->type == 'file' ? '<a href="'.route('download_attachment', $row->file->token).'">'.$row->file->filename.'</a>' : $row->value !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif



                </div>
            </div>


        </div>
    </form>
@endsection
