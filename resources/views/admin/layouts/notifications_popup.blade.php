<li class="notifications dropdown">
    @if(\App\Models\User::find(auth()->user()->getAuthIdentifier())->unreadNotifications->count())
        <span class="counter bgc-red">{{\App\Models\User::find(auth()->user()->getAuthIdentifier())->unreadNotifications->count()}}</span>
    @endif
    <a href="" class="dropdown-toggle no-after" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti-bell"></i>
    </a>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <li class="pX-20 pY-15 bdB">
            <i class="ti-bell pR-10"></i>
            <span class="fsz-sm fw-600 c-grey-900">Notifications</span>
        </li>
        <li>
            <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm">
                @forelse(auth()->user()->notifications as $notification)
                    <li>
                        @if($notification->type == 'App\Notifications\Order\OrderCreated')
                            <a href="{{route('ch-admin.order.show', [$notification->data['id']])}}" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                <div class="peer peer-greed">
                                                    <span>
                                                          <span class="c-grey-600"><i class="fa fa-shopping-cart text-aqua"></i> You have received a new <span class="text-dark">Order (#{{$notification->data['id']}}).</span></span>
                                                    </span>
                                    <p class="m-0">
                                        <small class="fsz-xs">{{$notification->created_at->diffForHumans()}}</small>
                                    </p>
                                </div>
                            </a>
                        @endif

                        @if($notification->type == 'App\Notifications\Order\MessageAdded')
                            <a href="{{route('ch-admin.order.messages', [$notification->data['order_id']])}}" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                <div class="peer peer-greed">
                                                    <span>
                                                          <span class="c-grey-600"><i class="fa fa-envelope text-aqua"></i> You have new message posted in <span class="text-dark">Order#{{$notification->data['order_id']}}</span>
                                                          </span>
                                                    </span>
                                    <p class="m-0">
                                        <small class="fsz-xs">{{$notification->created_at->diffForHumans()}}</small>
                                    </p>
                                </div>
                            </a>
                        @endif
                    </li>
                @empty
                    <li>
                        <a href="#" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                            No new notifications.
                        </a>
                    </li>
                @endforelse
            </ul>
        </li>
        <li class="pX-20 pY-15 ta-c bdT">
                            <span>
                              <a href="#" class="c-grey-600 cH-blue fsz-sm td-n clear-notifications">Clear Notifications <i class="ti-check fsz-xs mL-10"></i></a>
                            </span>
        </li>
    </ul>
</li>
