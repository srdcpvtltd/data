@if ( setting('social_login.enabled', 'no') == 'yes' )
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>@lang('auth.heading|Login With')</h1>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <div class="">

                    @if ( setting('services.facebook.enabled') == 'yes' )
                    <a title="@lang('auth.Login with Facebook')" href="{{ site_url('/auth/facebook') }}" class="btn btn-facebook"><img src="{{ url('assets/themes/default/img/facebook.png') }}" alt="@lang('auth.Login with Facebook')"></a>
                    @endif

                    @if ( setting('services.twitter.enabled') == 'yes' )
                    <a title="@lang('auth.Login with Twitter')" href="{{ site_url('/auth/twitter') }}" class="btn btn-twitter"><i class="fa fa-twitter"></i> Twitter</a>
                    @endif

                    @if ( setting('services.envato.enabled') == 'yes' )
                    <a title="@lang('auth.Login with Envato')" href="{{ site_url('/auth/envato') }}" class="btn btn-github"><img src="{{ url('assets/themes/default/img/envato.png') }}" alt="@lang('auth.Login with Envato')"></a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endif
