<div class="box-white">
    <div v-if="updating" class='overlay'></div>

    <div class="summary">
        <h3 class="box-heading text-uppercase">@lang('cart.Summary')</h3>

        <div role="alert" class='alert alert-warning' style="display: none;"></div>

        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div class="row clearfix">
                    <figure class="col-md-3 mb-0">
                        @if($product->hasMedia('gallery'))
                            <img src="{{$product->firstMedia('gallery')->getUrl('medium')}}"
                                 class="img-responsive" alt="{{$product->name}}">
                        @else
                            <img src="{{get_placeholder_img()}}" class="img-responsive"
                                 alt="{{$product->name}}">
                        @endif
                    </figure>

                    <div class="col-md-9 service-content">
                        <h4 class="mb-0">{{$product->name}}</h4>
                    </div>
                </div>
            </li>
            @foreach( $cart->getCartItemsTransformed() as $row )
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">{!! $row->name !!}</h6>
                    </div>
                    <span class="text-muted">{!! ch_format_price($row->price) !!}</span>
                </li>
            @endforeach

            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                    <h6 class="my-0">@lang('cart.Subtotal')</h6>
                </div>
                <span class="text-fuchsia"><span data-addon-total="0">{!! $cart->getSubtotalFormatted() !!}</span></span>
            </li>


            @if ( setting('taxes.enabled', 'no') == 'yes' )
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">@lang('cart.Tax')</h6>
                    </div>
                    <span class="text-warning"><span data-addon-total="0">{!! $cart->getTaxFormatted() !!}</span></span>
                </li>
            @endif


            @if (CartService::discountFloat() > 0)
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">@lang('cart.Discount')</h6>
                    </div>
                    <span class="text-success"><span data-addon-total="0">({!! ch_format_price(\CartService::discountFloat()) !!})</span></span>
                </li>
            @endif

            <li class="list-group-item d-flex justify-content-between">
                <span>@lang('cart.Total')</span>
                <strong><span data-summ-total="0">{!! $cart->getTotalFormatted() !!}</span></strong>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <h3>@lang('cart.Total') <span data-total="{!! $cart->getTotalFormatted()!!}">{!! $cart->getTotalFormatted() !!}</span></h3>
            </div>
        </div>
    </div>
</div>
