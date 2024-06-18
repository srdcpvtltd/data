@extends('themes.default.app', ['title' => trans('auth.title|Verify Your Email Address')])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@lang('auth.heading|Verify Your Email Address')</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            @lang('auth.A new verification link has been sent to your email address.')
                        </div>
                    @endif

                    @lang('auth.Before proceeding, please check your email for a verification link.<br/>If you did not receive the email,')
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
		                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">@lang('auth.button|Click here to request new link.')</button>.
	                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
