<div class="form-group payment-methods">

    @if ( is_gateway_configured('paypal') )
        <div class="form-check payment-method">
            <input type="radio" class="form-check-input" id="paypal" name="payment_method" checked value="paypal">
            <label class="form-check-label" for="paypal">
                <img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg"
                     alt="paypal">
            </label>
        </div>
    @endif

    @if ( is_gateway_configured('stripe') )
        <div class="form-check payment-method">
            <input type="radio" class="form-check-input" name="payment_method" value="stripe" id="stripe" required>
            <label class="form-check-label" for="stripe">@lang('cart.Credit Card via Stripe')</label>
        </div>
    @endif

    @if ( is_gateway_configured('offline_payments') )
        <div class="custom-control custom-radio">
            <input type="radio" class="form-check-input" name="payment_method" value="offline_payments" id="offline_payments" required>
            <label class="form-control-label" for="offline_payments"> {{setting('offline_payments.title')}}</label>
        </div>
    @endif
</div>

@if ( is_gateway_configured('stripe') )
    <div class="stripe mb-4" style="display: none;" data-method='stripe'>
        <label for="card-element">
            @lang('cart.Credit or debit card')
        </label>
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert" class='alert alert-warning' style="display: none;"></div>
    </div>
@endif

@if(is_gateway_configured('offline_payments'))
    <div class="form-row stripe" style="display: none;" data-method='offline_payments'>
        <p class="alert alert-info">{!! nl2br(setting('offline_payments.description')) !!}</p>
    </div>
@endif
