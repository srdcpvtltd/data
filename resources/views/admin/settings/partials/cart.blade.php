<div class="mb-3 row">
    <label for="settings[checkout][billing_info]" class="col-sm-2 control-label">Ask Billing Info on Checkout</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[checkout][billing_info]" name="settings[checkout][billing_info]">
            <option value="1" @if ( old('settings.checkout.billing_info', setting('checkout.billing_info') ) == '1' ) SELECTED @endif>Enable</option>
            <option value="0" @if ( old('settings.checkout.billing_info', setting('checkout.billing_info') ) == '0' ) SELECTED @endif>Disable</option>
        </select>
        <div class="text-muted">Allow you to hide and show billing information form on checkout page.</div>
    </div>
</div>

<div class="mb-3 row">
    <label for="settings[checkout][guest_checkout]" class="col-sm-2 control-label">Guest Checkout</label>
    <div class="col-sm-4">
        <select class="form-control" id="settings[checkout][guest_checkout]" name="settings[checkout][guest_checkout]">
            <option value="0" @if ( old('settings.checkout.guest_checkout', setting('checkout.guest_checkout') ) == '0' ) SELECTED @endif>Disable</option>
            <option value="1" @if ( old('settings.checkout.guest_checkout', setting('checkout.guest_checkout') ) == '1' ) SELECTED @endif>Enable</option>
        </select>
        <div class="text-muted text-sm">Allow your customers to place an order without login and without creating an account.</div>
    </div>
</div>
