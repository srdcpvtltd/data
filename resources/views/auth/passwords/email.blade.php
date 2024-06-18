@extends('themes.default.app', ['title' => trans('auth.heading|Reset Password')])

@section('content')
<div class="container page-ctn">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>@lang('auth.heading|Reset Password')</h1>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('auth.Email Address')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
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
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('auth.button|Send Password Reset Link')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(setting('recaptcha.enabled') == 'on')
    @push('ch_header')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush
@endif
