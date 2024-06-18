<h2 class="mb-0">@lang('order.heading|Order Details') #{{$order->id}}</h2>
<span class="d-inline-block mb-4">{!! $order->status_text !!}</span>


<div class="row">
    <div class="col-7">
        <p class="lead">@lang('order.Item Purchased')</p>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr class="text-uppercase">
                    <th>@lang('order.Item name')</th>
                    <th>@lang('order.Total')</th>
                </tr>
                </thead>
                <tbody>

                @foreach ( $order->items as $item )
                    <tr>
                        <td>{!! $item->name() !!} <strong>x {{ $item->qty() }}</strong></td>
                        <td>{!! ch_format_price( $item->total(), $order->currency() ) !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-5">
        <p class="lead">@lang('order.Summary')</p>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th class="w-50p">@lang('order.Payment method'):</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->PaymentMethod())) }}</td>
                </tr>

                <tr>
                    <th class="w-50p">@lang('order.Subtotal'):</th>
                    <td>{!! $order->subtotal() !!}</td>
                </tr>
                @if($order->getMeta('discounted'))
                    <tr>
                        <th>@lang('order.Coupon code')</th>
                        <td>{!! $order->getMeta('coupon_code') !!}</td>
                    </tr>
                    <tr>
                        <th>@lang('order.Coupon amount')</th>
                        <td>{!! ch_format_price($order->getMeta('discount_amount'), $order->getMeta('_order_currency')) !!}</td>
                    </tr>
                @endif
                <tr>
                    <th>@lang('order.Tax')</th>
                    <td>{!! $order->taxTotal() !!}</td>
                </tr>
                <tr>
                    <th>@lang('order.Total'):</th>
                    <td>{!! $order->totalFormatted() !!}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@if($order->customFields->count())
    <p class="lead">@lang('order.Submitted data')</p>
    <div class="table-responsive">
        <table class="table">
            @foreach($order->customFields as $row)
                @if($row->type == 'file' && !isset($row->file->token))
                    @continue
                @endif
                <tr>
                    <th><strong>{{$row->label}}</strong></th>
                    <td>{!! $row->type == 'file' ? '<a href="'.URL::temporarySignedRoute('download_attachment', now()->addMinutes(180), [$row->file->token, 'key' => $order->getMeta('order_key')]).'">'.$row->file->filename.'</a>' : $row->value !!}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

@if($order->hasMedia('attachments'))
    <div class="panel panel-default">
        <div class="panel-heading">@lang('order.Attachments')</div>
        <div class="panel-body">
            @foreach($order->getMedia('attachments') as $media)
                @if(!isset($media->token))
                    @continue;
                @endif
                <div class="row"
                     @if(!$loop->last) style="border-bottom: 1px solid #EEE; padding-bottom: 12px; margin-bottom: 12px;"@endif>
                    <div class="col-md-12"><a
                            href="{{URL::temporarySignedRoute('download_attachment', now()->addMinutes(180), [$media->token, 'key' => $order->getMeta('order_key')])}}"><strong>{{$media->Basename}}</strong></a>
                        (@lang('order.Attachments submitted') {{$media->created_at->diffForHumans()}})
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if($order->hasMedia('downloads') && ($order->status == 'completed' || $order->status == 'processing'))
    <div class="row">
        <div class="col-md-3 col-sm-3"><strong>@lang('order.Downloads/Files')</strong></div>
        <div class="col-md-9 col-sm-9">
            @foreach($order->getMedia('downloads') as $media)
                -
                <a href="{{URL::temporarySignedRoute('download_attachment', now()->addMinutes(180), [$media->token, 'key' => $order->getMeta('order_key')])}}"><strong>{{$media->Basename}}</strong></a>
                <br>
            @endforeach
        </div>
    </div>
@endif
