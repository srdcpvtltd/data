<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <title>@yield('title') | ChargePanda</title>
    <meta name="_token" content="{!! csrf_token() !!}">
    <style>
        #loader {
            transition: all 0.3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000;
        }

        #loader.fadeOut {
            opacity: 0;
            visibility: hidden;
        }


        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
            animation: sk-scaleout 1.0s infinite ease-in-out;
        }

        @-webkit-keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0)
            }
            100% {
                -webkit-transform: scale(1.0);
                opacity: 0;
            }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }
            100% {
                -webkit-transform: scale(1.0);
                transform: scale(1.0);
                opacity: 0;
            }
        }
    </style>
    <script src="{{url('assets/backend/js/jquery.min.js')}}"></script>
    <link href="{{url('assets/backend/css/app.css')}}" rel="stylesheet">
    <script>
        var base_url = '{{rtrim(site_url(), '/')}}';
    </script>
    @stack('head')
    @livewireStyles
</head>
<body class="app">


<div id="loader">
    <div class="spinner"></div>
</div>

<script>
    window.addEventListener('load', function load() {
        const loader = document.getElementById('loader');
        setTimeout(function () {
            loader.classList.add('fadeOut');
        }, 300);
    });
</script>


<div>
    <!-- #Left Sidebar ==================== -->
    <div class="sidebar">
        <div class="sidebar-inner">
            <!-- ### $Sidebar Header ### -->
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <a class="sidebar-link td-n" href="{{site_url('/ch-admin')}}">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer">
                                    <div class="logo text-center">
                                        <img src="{{site_url('/assets/img/icon.png')}}" class="img-responsive" style="width: 48px; margin-top: 10px;" alt="">
                                    </div>
                                </div>
                                <div class="peer peer-greed">
                                    <h5 class="lh-1 mB-0 logo-text"><strong>ChargePanda</strong></h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="peer">
                        <div class="mobile-toggle sidebar-toggle">
                            <a href="" class="td-n">
                                <i class="ti-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ### $Sidebar Menu ### -->
            <ul class="sidebar-menu scrollable pos-r">
                <li class="nav-item mT-30 actived">
                    <a class="sidebar-link" href="{{route(('ch-admin.ch_admin_dashboard'))}}">
                        <span class="icon-holder">
                          <i class="c-blue-500 ti-home"></i>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.product.index')}}">
                <span class="icon-holder">
                  <i class="c-deep-orange-500 ti-calendar"></i>
                </span>
                        <span class="title">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.category.index')}}">
                        <span class="icon-holder">
                            <i class="c-cyan-500 ti-folder"></i>
                        </span>
                        <span class="title">Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.order.index')}}">
                        <span class="icon-holder">
                          <i class="c-light-blue-500 ti-pencil"></i>
                        </span>
                        <span class="title">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.coupon.index')}}">
                <span class="icon-holder">
                  <i class="c-deep-purple-500 ti-comment-alt"></i>
                </span>
                        <span class="title">Coupons</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.messages.index')}}">
                        <span class="icon-holder">
                          <i class="c-light-blue-a700 ti-email"></i>
                        </span>
                        <span class="title">Message</span>
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="sidebar-link" href="{{route('ch-admin.form.index')}}">--}}
{{--                <span class="icon-holder">--}}
{{--                  <i class="c-indigo-500 ti-bar-chart"></i>--}}
{{--                </span>--}}
{{--                        <span class="title">Forms</span>--}}
{{--                    </a>--}}
{{--                </li>--}}


                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.user.index')}}">
                        <span class="icon-holder">
                            <i class="c-green-500 ti-user"></i>
                        </span>
                        <span class="title">Users</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="sidebar-link" href="{{route('ch-admin.language.index')}}">
                        <span class="icon-holder">
                            <i class="c-pink-500 ti-world"></i>
                        </span>
                        <span class="title">Languages</span>
                    </a>
                </li>

                <li class="nav-item dropdown {{ch_active_item('settings', 'open')}}">
                    <a class="dropdown-toggle" href="javascript:void(0);">
                        <span class="icon-holder">
                          <i class="c-orange-500 ti-layout-list-thumb"></i>
                        </span>
                        <span class="title">Settings</span>
                        <span class="arrow">
                          <i class="ti-angle-right"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu" {{ch_active_item('settings', 'style="display: block;"')}}>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['general'])}}">General</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['cart'])}}">Cart / Checkout</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['seo'])}}">SEO</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['mail'])}}">Mail</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['payments'])}}">Payment</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['taxes'])}}">Taxes</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['social_logins'])}}">Social Login</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['update'])}}">Update</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['site_pages'])}}">Site Pages</a>
                        </li>
                        <li>
                            <a class="sidebar-link" href="{{route('ch-admin.settings.show', ['code_analytics'])}}">Code & Analytics</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- #Main ============================ -->
    <div class="page-container">
        <!-- ### $Topbar ### -->
        <div class="header navbar">
            <div class="header-container">
                <ul class="nav-left">
                    <li>
                        <a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);">
                            <i class="ti-menu"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav-right">
                    @include('admin.layouts.notifications_popup')
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="peer mR-10">
                                <img class="w-2r bdrs-50p" src="{{ get_gravatar( \Auth()->user()->email ) }}" alt="{{ \Auth()->user()->name }}">
                            </div>
                            <div class="peer">
                                <span class="fsz-sm c-grey-900">{{ \Auth()->user()->name }}</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu fsz-sm" aria-labelledby="dropdownMenuLink">
                            <li>
                                <a href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                    <i class="ti-power-off mR-10"></i>
                                    <span>Logout</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ### $App Screen Content ### -->
        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            @if(env('DEMO_SITE'))
                                <div class="alert alert-info">
                                    <p class="mb-0"><i class="fa fa-info-circle"></i> This website is a demonstration. Admin actions are not functional on this platform. Its purpose is solely to showcase the fundamental concept.</p>
                                </div>
                            @endif

                            @include('flash::message')
                        </div>

                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

        @if(Route::currentRouteName() != 'ch-admin.order.messages')
            <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
                <span>Copyright &copy; {{date('Y')}} ChargePanda. All rights reserved.</span>
            </footer>
        @endif
    </div>
</div>

<script src="{{url('assets/backend/js/vendor.js')}}"></script>
<script src="{{url('assets/backend/js/main.js')}}"></script>
@livewireScripts
@stack('scripts')
</body>
</html>
