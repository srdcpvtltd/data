<div>
    <div class="full-container">
        <div class="peers pos-r">

            <div class="peer bdR" id="chat-sidebar" style="--dynamic-height: {{isset($order) ? 125 : 0}}px;">
                <div class="layers h-100">

                    <div class="bdB layer w-100">
                    </div>

                    @forelse ($orders as $orderDetails)
                        <div class="layer w-100 scrollable pos-r" wire:click="openChat({{$orderDetails->id}})">
                            <div class="peers fxw-nw ai-c p-20 bdB bgc-white bgcH-grey-50 cur-p{{isset($order) && $order->id === $orderDetails->id ? ' active-chat' : ''}}">
                                <div class="peer">
                                    <img src="{{get_gravatar($orderDetails->user->email)}}" alt="" class="w-3r h-3r bdrs-50p">
                                </div>
                                <div class="peer peer-greed pL-20">
                                    <h6 class="mB-0 lh-1 fw-400">Order #{{$orderDetails->id}} @if($orderDetails->UnreadMessagesCount(auth()->user()->getAuthIdentifier()))
                                            <span class="badge bg-danger">{{$orderDetails->UnreadMessagesCount(auth()->user()->getAuthIdentifier())}}</span>
                                        @endif</h6>
                                    <small class="lh-1 c-green-500">{{substr($orderDetails->lastMessage(), 0, 15)}}...</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No Orders found.</p>
                    @endforelse
                </div>
            </div>

            @if(isset($order))
                <div class="peer peer-greed" id="chat-box" style="--dynamic-height: 125px;">
                    <div class="layers h-100">
                        <div class="layer w-100">

                            <div class="peers fxw-nw jc-sb ai-c pY-20 pX-30 bgc-white">
                                <div class="peers ai-c">
                                    <div class="peer d-n@md+">
                                        <a href="" title="" id="chat-sidebar-toggle" class="td-n c-grey-900 cH-blue-500 mR-30">
                                            <i class="ti-menu"></i>
                                        </a>
                                    </div>
                                    <div class="peer mR-20">
                                        <img src="{{get_gravatar($order->user->email)}}" alt="" class="w-3r h-3r bdrs-50p">
                                    </div>
                                    <div class="peer">
                                        <h6 class="mB-0"><span class="fw-bold">Order ID:</span> #{{$order->id}} <i>({{$order->status}})</i></h6>
                                        <p class="mb-0"><span class="fw-bold">Customer: </span>{{$order->user->name}} | <span class="fw-bold">Created on: </span>{{$order->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layer w-100 fxg-1 bgc-grey-200 scrollable pos-r">
                            <div class="p-20 gapY-15">
                                @foreach($messages as $message)
                                    @if($message->user_id != $receiver_id)
                                        <div class="peers fxw-nw">
                                            <div class="peer mR-20">
                                                <img class="w-2r bdrs-50p" src="{{$receiverAvatar}}" alt="">
                                            </div>
                                            <div class="peer peer-greed">
                                                <div class="layers ai-fs gapY-5">
                                                    <div class="layer">
                                                        <div class="peers fxw-nw ai-c pY-3 pX-10 bgc-white bdrs-2 lh-3/2">
                                                            <div class="peer-greed">
                                                                <div><strong>{{$message->user->name}}</strong></div>
                                                                <span>{{$message->content}}</span>
                                                                @if($message->hasMedia('attachments'))
                                                                    <div class="attachments rounded px-1">
                                                                        @foreach($message->getMedia('attachments') as $attachment)
                                                                            <p><i class="fa fa-paperclip"></i>
                                                                                <a href="{{$attachment->tempUrl}}">{{$attachment->name}}</a>
                                                                            </p>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                                <div class="receive-time text-end" title="{{$message->created_at}}">{{$message->created_at->format('M d, Y | H:i')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="peers fxw-nw ai-fe">
                                            <div class="peer ord-1 mL-20">
                                                <img class="w-2r bdrs-50p" src="{{$senderAvatar}}" alt="">
                                            </div>
                                            <div class="peer peer-greed ord-0">
                                                <div class="layers ai-fe gapY-10">
                                                    <div class="layer">
                                                        <div class="peers fxw-nw ai-c pY-3 pX-10 bgc-white bdrs-2 lh-3/2">
                                                            <div class="peer-greed ord-0">
                                                                <div><strong>You</strong></div>
                                                                <span>{{$message->content}}</span>
                                                                @if($message->hasMedia('attachments'))
                                                                    <div class="attachments rounded px-1">
                                                                        @foreach($message->getMedia('attachments') as $attachment)
                                                                            <p><i class="fa fa-paperclip"></i>
                                                                                <a href="{{$attachment->tempUrl}}">{{$attachment->name}}</a>
                                                                            </p>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                                <div class="receive-time text-end" title="{{$message->created_at}}">{{$message->created_at->format('M d, Y | H:i')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        @if(isset($order))
            <div class="send-message-ctn">
                <div class="p-20 bdT bgc-white">

                    <div class="row">
                        <div class="col-md-12">
                            @if(count($attachments) > 0)
                                @foreach($attachments as $attachment)
                                    <div class="attachment-row d-inline-block dz-processing dz-complete">
                                        <div class="dz-file-info">
                                            <p>
                                                <a class="btn btn-xs" wire:click="deleteMedia({{$attachment['id']}})">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                                <i class="fa fa-paperclip"></i>
                                                <span class="name mb-0">{{$attachment['filename'] . '.' . $attachment['extension']}}</span>
                                                <strong class="size mb-0" data-dz-size="">{{$attachment['ReadableSize']}}</strong>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="col-md-12 attachments-pr" wire:ignore></div>
                    </div>

                    <div class="pos-r attachments">
                        <input type="text"
                               wire:model.defer="message"
                               wire:keydown.enter="sendMessage"
                               wire:loading.attr="disabled"
                               wire:target="sendMessage"
                               class="form-control bdrs-10em m-0"
                               placeholder="Type message and hit Enter...">

                        <button wire:ignore type="button"
                                wire:loading.attr="disabled"
                                wire:target="sendMessage"
                                id="attachments-uploader"
                                data-media-config='{"previewsContainer": ".attachments-pr", "disk": "local", "tag": "attachments"}'
                                class="btn btn-primary bdrs-50p w-2r p-0 h-2r pos-a r-1 t-1 btn-color dropzone attachment-upload-btn"></button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@push('scripts')
    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/backend/js/media.js') }}"></script>
    <script type="text/x-handlebars-template" id="filePreviewTemplate">

        <div class="attachment-row d-inline-block">
            <div class="dz-file-info">
                <p><a data-dz-remove class="btn btn-sm btn-danger cancel">
                        <i class="fa fa-stop-circle" title="Stop"></i>
                    </a> <a data-dz-remove class="btn btn-xs">
                        <i class="fa fa-trash text-danger"></i>
                    </a> <i class="fa fa-paperclip"></i> <span class="name mb-0" data-dz-name></span> <span class="size mb-0" data-dz-size></span></p>
                <strong class="error text-danger" data-dz-errormessage></strong>
            </div>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"
                 aria-valuenow="0">
                <div class="progress-bar progress-bar-success" style="width:100%;" data-dz-uploadprogress></div>
            </div>
        </div>

    </script>
    <script>

        @if(Route::currentRouteName() == 'ch-admin.order.messages')
            const chatBox = document.querySelector("#chat-box");
            const attachments = document.querySelector(".attachments-pr");

            window.addEventListener("scrollToBottom", function () {
                chatBox.scrollTop = chatBox.scrollHeight;
                attachments.innerHTML = '';

            });

            Livewire.hook('message.processed', function () {
                chatBox.scrollTop = chatBox.scrollHeight;
                attachments.innerHTML = '';
            });

            window.addEventListener("fileUploaded", function (e) {
                Livewire.emit('setAttachmentIds', e.detail.product_id);
                const dynamicDivHeight = $(".send-message-ctn").outerHeight();

                $("#chat-sidebar, #chat-box ").css("--dynamic-height", dynamicDivHeight + "px").promise().done(function () {
                    window.scrollTo(0, document.body.scrollHeight);
                });
            });

            Livewire.hook('component.initialized', function () {
                chatBox.scrollTop = chatBox.scrollHeight;
            });

            document.addEventListener('livewire:load', function () {
                initializeDropzone(document.querySelector('.dropzone'));
            });
        @endif
    </script>
@endpush

