@extends('themes.default.app', ['title' => trans('auth.title|Login')])

@section('content')
<div class="container page-ctn">
    <div class="row">
        <div class="col-md-6 @if ( setting('social_login.enabled', 'no') == 'no' ) col-md-offset-2 @endif">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>@lang('auth.heading|Login')</h1>
                </div>

                <div class="panel-body">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('auth.Email Address')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('auth.Password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('auth.Remember Me')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('recaptcha') ? ' has-error' : '' }}">
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
                                <button type="submit" class="btn btn-primary">
                                    @lang('auth.button|Login')
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    @lang('auth.button|Forgot Your Password?')
                                </a>
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
