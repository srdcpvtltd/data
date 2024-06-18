@extends('themes.default.app', ['title' => trans('cart.title|Cart')])

@section('content')

    <form action="{{ route('ch_order_save') }}" method="post" id="payment-form">
        <div class="container page-ctn" id="cart">
            <div class="row">

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @endif
                {{csrf_field()}}
                {{method_field('POST')}}
                <div class="col-md-7">

                    @if(setting('checkout.billing_info', '1') == '1')

                        <div class="box-white order-billing mb-4">
                            <h3 class="box-heading">@lang('cart.Billing info'):</h3>
                            <div class="row">
                                <div class="form-group mb-3 col-md-6{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name">@lang('cart.First name')</label>

                                    <input id="first_name" type="text" required class="form-control"
                                           name="first_name"
                                           value="{{ auth()->check() ? Auth::user()->first_name : old('first_name') }}">

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>


                                <div class="form-group mb-3 col-md-6{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="last_name">@lang('cart.Last name')</label>

                                    <input id="last_name" type="text" required class="form-control" name="last_name"
                                           value="{{ auth()->check() ? Auth::user()->last_name : old('last_name') }}">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @if(!auth()->check() && setting('checkout.guest_checkout', '0') == '1')
                                    <div class="form-group mb-3 col-md-6{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">@lang('cart.Email Address')</label>


                                        <input id="email" type="text" required class="form-control" name="email"
                                               value="{{ old('email') }}">

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                @endif

                                <div class="form-group mb-3 col-md-4{{ $errors->has('usermeta.billing_address') ? ' has-error' : '' }}">
                                    <label for="usermeta[billing_address]">@lang('cart.Address')</label>
                                    <input id="usermeta[billing_address]" required type="text" class="form-control"
                                           name="usermeta[billing_address]"
                                           value="{{ auth()->check() ? Auth::user()->billing_address : old('usermeta.billing_address') }}">

                                    @if ($errors->has('usermeta.billing_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('usermeta.billing_address') }}</strong>
                                        </span>
                                    @endif

                                </div>


                                <div class="form-group mb-3 col-md-4{{ $errors->has('usermeta.billing_city') ? ' has-error' : '' }}">
                                    <label for="usermeta[billing_city]">@lang('cart.City')</label>
                                    <input id="usermeta[billing_city]" required type="text" class="form-control"
                                           name="usermeta[billing_city]"
                                           value="{{ auth()->check() ? Auth::user()->billing_city : old('usermeta.billing_city')  }}">

                                    @if ($errors->has('usermeta.billing_city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('usermeta.billing_city') }}</strong>
                                        </span>
                                    @endif

                                </div>

                                <div class="form-group mb-3 col-md-4{{ $errors->has('usermeta.billing_zip') ? ' has-error' : '' }}">
                                    <label for="usermeta[billing_zip]">@lang('cart.Zip code')</label>
                                    <input id="usermeta[billing_zip]" required type="text" class="form-control"
                                           name="usermeta[billing_zip]"
                                           value="{{ auth()->check() ? Auth::user()->billing_zip : old('usermeta.billing_zip') }}">

                                    @if ($errors->has('usermeta.billing_zip'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('usermeta.billing_zip') }}</strong>
                                        </span>
                                    @endif
                                </div>


                                <div class="form-group mb-3 col-md-4{{ $errors->has('usermeta.billing_country') ? ' has-error' : '' }}">
                                    <label for="usermeta[billing_country]">@lang('cart.Country')</label>
                                    <select id="usermeta[billing_country]" required
                                            v-model="cartData.billing_country" type="text" class="form-control"
                                            name="usermeta[billing_country]" v-on:change="updateStates">
                                        <option value="0">@lang('cart.Select country')</option>
                                        <option v-for="country in countries" :value="country.id">@{{country.name}}
                                        </option>
                                    </select>

                                    @if ($errors->has('usermeta.billing_country'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('usermeta.billing_country') }}</strong>
                                            </span>
                                    @endif
                                </div>


                                <div class="form-group mb-3 col-md-4{{ $errors->has('usermeta.billing_state') ? ' has-error' : '' }}">
                                    <label for="usermeta[billing_state]">@lang('cart.State')</label>

                                    <div class="user-state">
                                        <div class='state-container'>
                                            <select id="usermeta[billing_state]" required
                                                    v-model="cartData.billing_state" type="text" class="form-control"
                                                    name="usermeta[billing_state]">

                                                <option value="0">@lang('cart.Select state')</option>
                                                <option v-for="state in states" :value="state.id">@{{state.name}}
                                                </option>

                                            </select>
                                        </div>
                                        @if ($errors->has('usermeta.billing_state'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('usermeta.billing_state') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif

                    @if($product->addons->count())
                        <div class="box-white order-page mb-4">
                            <h3 class="w-700 mt-3 mb-2">@lang('cart.Addons')</h3>
                            @foreach( $product->addons as $addon )

                                <div class="custom-control form-check addon form-control">

                                    <span class="float-end">{!! ch_format_price($addon->price) !!}</span>

                                    <input id="addon-id-{{$addon->id}}" type="checkbox" name="addons[]" class="form-check-input" value="{{$addon->id}}"
                                           v-model="cartData.addons">
                                    <label for="addon-id-{{$addon->id}}" class="form-check-label">{{$addon->name}}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="box-white mb-4 discount-box">
                        <h3 class="w-700 mb-2">@lang('cart.Have a coupon code?')</h3>
                        <div class="input-group mb-3">
                            <div class="col-6">
                                <input type="text" class="form-control" v-model="couponData.code" value=""
                                       placeholder="@lang('cart.Coupon code')" :disabled="couponData.applied">
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" @click="applyCoupon()"
                                        v-if="!couponData.applied"><i class="fas fa-check"></i></button>
                                <button type="button" class="btn btn-danger" @click="removeCoupon()"
                                        v-if="couponData.applied"><i class="far fa-times-circle"></i></button>
                            </div>

                        </div>
                        <p class="alert alert-danger" v-if="couponData.error" v-text="couponData.error"></p>
                    </div>

                    @if (isset($plan) && $plan->price > 0 || $product->price > 0)

                        @if ( is_gateway_configured('stripe') || is_gateway_configured('paypal') || is_gateway_configured('offline_payments') || is_gateway_configured('razorpay') || is_gateway_configured('instamojo') )
                            @include('core.payment_gateways')
                            @if(  Auth::check() )
                                <button id="submitForm" class="btn btn-primary btn-block btn-lg">@lang('cart.Place Order')</button>
                            @else
                                <p class="alert alert-warning">@lang('cart.You need to <a href=":login_url">login</a>/<a href=":register_url">register</a> to place an order.', [
                'login_url' => route('login').'?to=/cart',
                'register_url' => route('register'),
                ])</p>
                                @if(setting('checkout.guest_checkout', '0') == '1')
                                    <button id="submitForm" class="btn btn-primary btn-block btn-lg">@lang('cart.Checkout as a guest')</button>
                                @endif
                            @endif
                        @else
                            <p class="alert alert-warning"><i class="fa fa-info-circle"></i> @lang('cart.Payment gateway is not configured. Please contact site admin.')</p>
                        @endif

                    @else
                        <button id="submitForm" class="btn btn-primary btn-block btn-lg">@lang('cart.Place Order')</button>
                    @endif

                    @if($product->form_id)
                        <div class="box-white mb-5">
                            @if( $product->hasMeta('guideline') )
                                <h3 class="box-heading">@lang('cart.Provide information'):</h3>
                                <p>@lang('cart.Please provide the following data in order to complete the task.')</p>
                                {!! nl2br( $product->getMeta('guideline') ) !!}
                            @endif
                            <br><br>
                            {!! $product->form->content ?? '' !!}

                        </div>
                    @endif

                </div><!-- /.col-md-7 -->


                <div class="col-md-5">
                    <div class="summary-container" data-sticky="true">
                        @include('core.order.summary')
                    </div>
                </div>

            </div>


        </div>
    </form>
    <script id="states-template" type="text/x-handlebars-template">
        <select id="usermeta[billing_state]" v-model="cartData.billing_state" class="form-control"
                name="usermeta[billing_state]">
            <option value="">@lang('cart.Select state')</option>
            @{{#each this}}
            <option value="@{{ @key }}">@{{this}}</option>
            @{{/each}}
        </select>
    </script>
@endsection

@push('ch_footer')

    @php
        $addon_ids = Cart::content()->filter(function($item){
            return $item->model instanceof \App\Models\Addon;
        })->pluck('id');

    @endphp

    <script src="//js.stripe.com/v3/"></script>

    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script>
        let upload_url = '{{site_url('cart/upload')}}';
        let product_id = '{{$product->form_id}}';
        let countries = {!! $countries !!};
        let states = {!! $states !!};
        let addons = {!! $addon_ids !!};
        let billing_country = '{{$user_country}}';
        let billing_state = '{{$user_state}}';
        let coupon_code = '{{session()->get('coupon_code')}}';
        let cart_url = '{{site_url('cart')}}';
    </script>
    <script src="{{ url('assets/themes/default/js/vue.min.js') }}"></script>
    <script src="{{ url('assets/themes/default/js/axios.min.js') }}"></script>
    <script src="{{ url('assets/themes/default/js/cart.js') }}"></script>
@endpush
