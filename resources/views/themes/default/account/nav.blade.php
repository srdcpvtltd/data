<div class="panel panel-default">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link" href="{{route('home')}}">@lang('account.Home')</a>
        <a class="nav-link{{Route::currentRouteName() == 'ch_user_orders' || Route::currentRouteName() == 'ch_order_view' ? ' active' : ''}}" href="{{route('ch_user_orders')}}">@lang('account.Orders')</a>
        <a class="nav-link{{Route::currentRouteName() == 'ch_edit_details' ? ' active' : ''}}" href="{{route('ch_edit_details')}}">@lang('account.Account Details')</a>
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-account').submit();">@lang('account.Logout')</a>
        <form id="logout-form-account" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>
