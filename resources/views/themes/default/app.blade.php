{{--Section: General--}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ session('lang_dir') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>{{ch_get_title(isset($title) ? $title : '')}}</title>

    @if(Route::is('home'))
        <meta name="description" content="{{\setting('seo.home_desc')}}"/>
    @endif

    @if(!Route::is('home') && isset($description))
        <meta name="description" content="{{$description}}"/>
    @endif

    {!! ch_get_favicon() !!}

    <link rel="canonical" href="{{site_url(request()->path())}}">
    <!-- Styles -->
    <link href="{{ url('assets/themes/default/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.typekit.net/nui5hxz.css">
    <script>
        const base_url = '{{rtrim(site_url(), '/')}}';
        let logged_in = false;
        @if(auth()->check())
            logged_in = true;
        @endif
        const stripe_key = {!! '"'.setting("stripe.pk").'"' !!};
    </script>
    @stack('ch_header')
    {!! setting('header_code') !!}
    <style>
        {!! setting('custom_css') !!}
    </style>
</head>
<body>
<div id="app">
    <section class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul class="nav nav-pills nav-account float-end">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">@lang('menu.Login')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">@lang('menu.Register')</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    @if ( \Auth::user()->hasRole('administrator') )
                                        <li class="nav-item"><a class="nav-link"
                                                                href="{{ site_url('/ch-admin') }}">@lang('menu.Admin')</a>
                                        </li>
                                    @endif

                                    <li class="nav-item"><a class="nav-link"
                                                            href="{{route('ch_user_orders')}}">@lang('menu.Orders')</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link"
                                                            href="{{route('ch_edit_details')}}">@lang('menu.Account details')</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"
                                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            @lang('menu.Logout')
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                        <li>
                            <button type="button" class="search-btn" onclick="document.getElementById('searchOverlay').style.display = 'block';"><i class="fa fa-search"></i></button>
                        </li>
                    </ul>

                    <div id="searchOverlay" class="overlay">
                        <span class="closebtn" onclick="document.getElementById('searchOverlay').style.display = 'none';" title="Close Overlay">×</span>
                        <div class="overlay-content">
                            <form class="search-form" action="{{route('search')}}">
                                <input type="text" class="form-control search-bar" name="q" placeholder="@lang('general.Search')"
                                               @isset($q) value="{{$q}}" @endisset/>
                            </form>
                        </div>
                    </div>

                    <ul class="nav nav-pills">
                        @if ($active_languages->count() > 1)
                            <li class="nav-item dropdown language-changer">
                                <a class="nav-link dropdown-toggle selected-lang" data-toggle="dropdown" href="#"
                                   role="button" aria-haspopup="true" aria-expanded="false"><span
                                            class="flag flag-{{$default_lang->locale}}"> </span> {{$default_lang->locale}}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="langdropdown">
                                    @foreach($active_languages as $language)
                                        @if((session()->has('locale') && $language->locale == session()->get('locale')) || $default_lang->locale == $language->locale)
                                            @continue
                                        @endif
                                        <a class="dropdown-item"
                                           href="{{route('switch_lang', [$language->locale])}}"><span
                                                    class="flag flag-{{$language->locale}}"></span> {{$language->locale}}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- transparent-light-txt -->
    <header class="site-header">

        <div class="container">
            <div class="row">
                <div class="col-lg-12 d-none d-xl-block d-lg-block">
                    <nav id="main-menu">
                        <ul class="sf-menu float-end">
                            {!! get_menu($categories) !!}
                        </ul>
                    </nav>

                    <div class="site-branding">
                        <h1 class="site-title">
                            <a href="{{ site_url('/') }}">
                                {!! get_logo() !!}
                            </a>
                        </h1>
                    </div>
                </div>

            </div>
        </div>

        <div class="container-fluid d-lg-none d-xl-none d-md-block d-sm-block">
            <div class="row">
                <div class="col-lg-12">
                    <nav id="mob-menu">

                    </nav>
                </div>
            </div>
        </div>

    </header>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('flash::message')
            </div>
        </div>
    </div>

    @yield('content')


    <footer class="site-footer clearfix">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0"><a href="{{ site_url('page/terms-of-service') }}">@lang('menu.Terms of service')</a> | <a
                                href="{{ site_url('page/privacy-policy') }}">@lang('menu.Privacy Policy')</a> | <a
                                href="{{ site_url('page/refund-policy') }}">@lang('menu.Refund Policy')</a> | <a
                                href="{{ site_url('page/contact') }}">@lang('menu.Contact')</a></p>
                    <p class="mb-0">@lang('general.&copy; Copyright :name. All rights reserved.', ['name'=> setting('app.name', 'ChargePanda')])</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Scripts -->
<script src="{{ url('assets/themes/default/js/jquery.min.js') }}"></script>
<script src="{{ url('assets/themes/default/js/app.js') }}"></script>
@stack('ch_footer')
{!! setting('footer_code') !!}
</body>
</html>
