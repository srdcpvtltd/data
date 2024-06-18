<div class="nav-tabs-custom">
    <ul class="nav nav-tabs nav-fill">
        <li class="nav-item">
            <button type="button" data-bs-target="#paypal" class="nav-link {{session('active_tab') == 'paypal' || is_null(session('active_tab')) ? 'active' : ''}}" data-bs-toggle="tab">PayPal</button>
        </li>
        <li class="nav-item">
            <button type="button" data-bs-target="#stripe" class="nav-link {{session('active_tab') == 'stripe' ? 'active' : ''}}" data-bs-toggle="tab">Stripe</button>
        </li>
        <li class="nav-item">
            <button type="button" data-bs-target="#offline-payments" class="nav-link {{session('active_tab') == 'offline-payments' ? 'active' : ''}}" data-bs-toggle="tab">Offline Payments</button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <input type="hidden" id="active_tab" name="active_tab" value="{{session('active_tab') == 'paypal' || is_null(session('active_tab')) ? 'paypal' : session('active_tab')}}">
        <div class="tab-pane fade {{session('active_tab') == 'paypal' || is_null(session('active_tab')) ? 'show active' : ''}}" id="paypal">


            <div class="mb-3 row">
                <label for="settings[paypal][enabled]" class="col-sm-2 control-label">Enable PayPal</label>
                <div class="col-sm-3">
                    <select class="form-control" id="settings[paypal][enabled]" name="settings[paypal][enabled]">
                        <option value="yes" @if ( old('settings.paypal.enabled', setting('paypal.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
                        <option value="no" @if ( old('settings.paypal.enabled', setting('paypal.enabled')) == 'no' ) SELECTED @endif>No</option>
                    </select>
                </div>
            </div>


            <div class="mb-3 row">
                <label for="settings[paypal][sandbox_mode]" class="col-sm-2 control-label">Enable Sandbox mode</label>
                <div class="col-sm-3">
                    <select class="form-control" id="settings[paypal][sandbox_mode]" name="settings[paypal][sandbox_mode]">
                        <option value="yes" @if ( old('settings.paypal.sandbox_mode', setting('paypal.sandbox_mode')) == 'yes' ) SELECTED @endif>Yes</option>
                        <option value="no" @if ( old('settings.paypal.sandbox_mode', setting('paypal.sandbox_mode')) == 'no' ) SELECTED @endif>No</option>
                    </select>
                    <p class="lh-1" style="font-size: 12px;">Please use <strong>Sandbox Account Settings</strong> below if Sandbox mode is enabled.</p>
                </div>
            </div>


            <h3 class="sub-settings">PayPal Account Settings</h3>


            <div class="mb-3 row">
                <label for="settings[paypal][username]" class="col-sm-2 control-label">PayPal API Username</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="settings[paypal][username]" name="settings[paypal][username]" value="{{ old('settings.paypal.username', setting('paypal.username')) }}">
                </div>
            </div>


            <div class="mb-3 row">
                <label for="settings[paypal][password]" class="col-sm-2 control-label">PayPal API Password</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="settings[paypal][password]" name="settings[paypal][password]" value="{{ old('settings.paypal.password', setting('paypal.password')) }}">
                </div>
            </div>


            <div class="mb-3 row">
                <label for="settings[paypal][signature]" class="col-sm-2 control-label">PayPal API Signature</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="settings[paypal][signature]" name="settings[paypal][signature]" value="{{ old('settings.paypal.signature', setting('paypal.signature')) }}">
                </div>
            </div>

        </div>

        <div class="tab-pane fade {{session('active_tab') == 'stripe' ? 'show active' : ''}}" id="stripe">

            <div class="mb-3 row">
                <label for="settings[stripe][enabled]" class="col-sm-2 control-label">Enable Stripe</label>
                <div class="col-sm-3">
                    <select class="form-control" id="settings[stripe][enabled]" name="settings[stripe][enabled]">
                        <option value="yes" @if ( old('settings.stripe.enabled', setting('stripe.enabled')) == 'yes' ) SELECTED @endif>Yes</option>
                        <option value="no" @if ( old('settings.stripe.enabled', setting('stripe.enabled')) == 'no' ) SELECTED @endif>No</option>
                    </select>
                </div>
            </div>


            <div class="mb-3 row">
                <label for="settings[stripe][sandbox_mode]" class="col-sm-2 control-label">Enable Test mode</label>
                <div class="col-sm-3">
                    <select class="form-control" id="settings[stripe][sandbox_mode]" name="settings[stripe][sandbox_mode]">
                        <option value="yes" @if ( old('settings.stripe.sandbox_mode', setting('stripe.sandbox_mode')) == 'yes' ) SELECTED @endif>Yes</option>
                        <option value="no" @if ( old('settings.stripe.sandbox_mode', setting('stripe.sandbox_mode')) == 'no' ) SELECTED @endif>No</option>
                    </select>
                    <p class="lh-1" style="font-size: 12px;">Please use <strong>Test Account Settings</strong> below if Test mode is enabled.</p>
                </div>
            </div>


            <h3 class="sub-settings">Stripe Account Settings</h3>


            <div class="mb-3 row">
                <label for="settings[stripe][pk]" class="col-sm-2 control-label">Stripe Publishable key</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="settings[stripe][pk]" name="settings[stripe][pk]" value="{{ old('settings.stripe.pk', setting('stripe.pk')) }}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="settings[stripe][sk]" class="col-sm-2 control-label">Stripe Secret key</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="settings[stripe][sk]" name="settings[stripe][sk]" value="{{ old('settings.stripe.sk', setting('stripe.sk')) }}">
                </div>
            </div>

        </div>

        <div class="tab-pane fade {{session('active_tab') == 'offline-payments' ? 'show active' : ''}}" id="offline-payments">

            <div class="mb-3 row">
                <label for="settings[offline_payments][enabled]" class="col-sm-2 control-label">Enable Offline
                    Payments</label>
                <div class="col-sm-3">
                    <select class="form-control" id="settings[offline_payments][enabled]"
                            name="settings[offline_payments][enabled]">
                        <option value="no"
                                @if ( old('settings.offline_payments.enabled', setting('offline_payments.enabled')) == 'no' ) SELECTED @endif>
                            No
                        </option>
                        <option value="yes"
                                @if ( old('settings.offline_payments.enabled', setting('offline_payments.enabled')) == 'yes' ) SELECTED @endif>
                            Yes
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="settings[offline_payments][order_status]" class="col-sm-2 control-label">Default Order Status</label>
                <div class="col-sm-3">
                    <select class="form-control" id="settings[offline_payments][order_status]"
                            name="settings[offline_payments][order_status]">
                        <option value="processing"
                                @if ( old('settings.offline_payments.order_status', setting('offline_payments.order_status')) == 'processing' ) SELECTED @endif>
                            Processing
                        </option>
                        <option value="pending"
                                @if ( old('settings.offline_payments.order_status', setting('offline_payments.order_status')) == 'pending' ) SELECTED @endif>
                            Pending
                        </option>
                        <option value="completed"
                                @if ( old('settings.offline_payments.order_status', setting('offline_payments.order_status')) == 'completed' ) SELECTED @endif>
                            Completed
                        </option>
                    </select>
                </div>
            </div>

            <h3 class="sub-settings">Offline Account Settings</h3>

            <div class="mb-3 row">
                <label for="settings[offline_payments][title]" class="col-sm-2 control-label">Payment Method
                    Title</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="settings[offline_payments][title]"
                           name="settings[offline_payments][title]"
                           value="{{ old('settings.offline_payments.title', setting('offline_payments.title')) }}">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="settings[offline_payments][description]" class="col-sm-2 control-label">Payment Method
                    Description</label>
                <div class="col-sm-4">
                    <textarea name="settings[offline_payments][description]"
                              id="settings[offline_payments][description]"
                              class="form-control">{{ old('settings.offline_payments.description', setting('offline_payments.description')) }}</textarea>
                </div>
            </div>
        </div>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>

@push('scripts')
    <script>
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            let target = $(e.target).attr("data-bs-target"); // activated tab
            let activeSection = target.replace('#', '');

            $('#active_tab').val(activeSection);
        });
    </script>
@endpush
