@extends('themes.default.app', ['title' => trans('auth.title|Register')])

@section('content')
<div class="container page-ctn">
    <div class="row">
        <div class="col-md-6 @if ( setting('social_login.enabled', 'no') == 'no' ) col-md-offset-2 @endif">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>@lang('auth.heading|Register')</h1>
                </div>

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group row mb-3{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <label for="first_name" class="col-md-12 control-label">@lang('auth.First Name')</label>
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>

                                @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="col-md-12 control-label">@lang('auth.Last Name')</label>
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <label for="email" class="col-md-12 control-label">@lang('auth.Email Address')</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <label for="password" class="col-md-12 control-label">@lang('auth.Password')</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="col-md-12 control-label">@lang('auth.Confirm Password')</label>

                                <div class="col-md-12">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3{{ $errors->has('recaptcha') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="g-recaptcha" data-sitekey="{{setting('recaptcha.api_site_key')}}"></div>

                                @if ($errors->has('recaptcha'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('recaptcha') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <p class="help-block">@lang('auth.By registering you agree to the <a href=":url">terms and conditions</a>.', ['url' => url('page/terms-of-service')])</p>
                                <button type="submit" class="btn btn-primary">
                                    @lang('auth.button|Register')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('auth.social')

    </div>
</div>
@endsection

@if(setting('recaptcha.enabled') == 'on')
    @push('ch_header')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush
@endif
