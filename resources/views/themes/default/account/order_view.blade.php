@extends('themes.default.app', ['title' => trans('order.title|Order Details')])

@section('content')
    <div class="container page-ctn">
        <div class="row">

            <div class="col-md-3 col-sm-3">
                @include('themes.default.account.nav')
            </div>

            <div class="col-md-9 col-sm-9">
                @include('themes.default.account.order_content')

                @if($order->status != 'cancelled')
                    @if($order->status != 'completed')

                    @else

                        @if ( $order->feedback(\Auth::user()->id) )

                            <h3 class="mt-5 mb-2">@lang('order.Feedback'):</h3>

                            <div class="form-group mb-3">
                                <p class="mb-1 text-dark"><strong>@lang('order.Rating')</strong></p>
                                <select class="posted" id="rating">
                                    @for( $x = 1; $x <= 5; $x++ )
                                        <option value="{{$x}} "{{$order->feedback(\Auth::user()->id)->rating == $x ? 'SELECTED' : ''}}>{{$x}}</option>
                                    @endfor
                                </select>
                            </div>

                            <p class="mb-1 text-dark"><strong>@lang('order.Comments'):</strong></p>
                            <p>{{$order->feedback(\Auth::user()->id)->content}}</p>


                        @else
                            <h3 class="mt-5">@lang('order.Please provide a feedback.')</h3>

                            <form action="" method="POST">
                                {{csrf_field()}}
                                {{method_field('PUT')}}

                                <div class="form-group">
                                    <label for="rating">@lang('order.Rating')</label>
                                    <select id="rating">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3 {{ $errors->has('content') ? ' has-error' : '' }}">

                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                             <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                                    @endif
                                    <label for="content">@lang('order.Comments')</label>
                                    <textarea name="content" class="form-control">{{ old('content') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="post_feedback" value="@lang('order.Submit Feedback')"
                                           class="btn btn-primary">
                                </div>
                            </form>
                        @endif



                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="chatbox chatbox22 chatbox--tray" id="chatbox">
        <div class="chatbox__title" @click="openChatBox()">
            <h5><a href="javascript:void(0)">@lang('order.Chat box') - @lang('order.heading|Order Details') #{{$order->id}}</a></h5>
            <button class="chatbox__title__close">
            <span>
                <svg viewBox="0 0 12 12" width="12px" height="12px">
                    <line stroke="#FFFFFF" x1="11.75" y1="0.25" x2="0.25" y2="11.75"></line>
                    <line stroke="#FFFFFF" x1="11.75" y1="11.75" x2="0.25" y2="0.25"></line>
                </svg>
            </span>
            </button>
        </div>
        <div class="chatbox__body">
            <!-- Sender Message-->
            <div v-for="message in messages"
                 :class="receiver_id != message.user_id ? 'media w-50 mb-3' : 'media w-50 ml-auto mb-3'"
                 v-bind:key="message.id">
                <div class="media-body ml-3">
                    <div class="rounded py-2 px-3" :class="receiver_id != message.user_id ? 'bg-white' : 'bg-primary'">
                        <span v-if="receiver_id != message.user_id" class="message-sender fw-bold text-dark"
                              v-text="message.user.name"></span>
                        <p class="text-small mb-0" :class="receiver_id == message.user_id ? 'text-white' : ''"
                           v-text="message.content"></p>
                    </div>
                    <div class="attachments rounded px-1" v-if="message.media.length"
                         :class="receiver_id == message.user_id ? 'bg-white' : 'bg-secondary bg-opacity-10'">
                        <p v-for="attachment in message.media" v-bind:key="attachment.id"><i
                                    class="fas fa-paperclip"></i> <a :href="attachment.TempUrl"
                                                                     v-text="attachment.name"></a></p>
                    </div>
                    <p class="small text-muted" v-text="message.created_at"></p>
                </div>
            </div>
        </div>
        <div class="files-preview"></div>
        <div class="panel-footer">
            <div class="input-group">
                <input id="btn-input" v-on:keyup.enter="sendMessage" type="text" v-model="message"
                       :disabled="isSendingMessage"
                       class="form-control input-sm chat_set_height" placeholder="@lang('order.Type your message here')"
                       tabindex="0" dir="ltr" spellcheck="false" autocomplete="off" autocorrect="off"
                       autocapitalize="off" contenteditable="true"/>

                <span class="input-group-btn">
                    <button class="btn bt_bg btn-sm" id="attachFiles"></button>
                    <button :disabled="message == '' || isSendingMessage == true" :class="message == '' ? '' : 'text-primary'"
                            class="btn bt_bg btn-sm" @click="sendMessage"><i class="fas fa-paper-plane"></i></button>
                </span>
            </div>
        </div>
    </div>



    <div id="chat-upload-template" class="d-none">
        <div class="files-preview-inner">
            <div class="dz-preview dz-file-preview row">
                <div class="col-5">
                    <div class="dz-details">
                        <div class="dz-filename"><span data-dz-name></span></div>
                    </div>
                </div>
                <div class="col-5 text-danger">
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                </div>
                <div class="dz-success-mark col-2 text-end text-danger"><a href="#" data-dz-remove><i
                                class="fas fa-trash"></i></a></div>
                <div class="dz-error-mark col-2 text-end text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            </div>
        </div>
    </div>
@endsection


@push('ch_footer')
    <script>
        const getMessagesUrl = '{{route('ch_get_messages', [$order->id])}}';
        const sendMessageUrl = '{{route('ch_store_message', [$order->id])}}';
        const uploadFileUrl = '{{route('ch_chat_upload')}}';
        const uploadAttachmentUrl = '{{route('ch_delete_attachment')}}';
        const order_id = {{$order->id}};
        const receiver_id = {{auth()->user()->getAuthIdentifier()}};
    </script>

    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/themes/default/js/vue.min.js') }}"></script>
    <script src="{{ url('assets/themes/default/js/axios.min.js') }}"></script>
    <script src="{{ url('assets/themes/default/js/moment.min.js') }}"></script>
    <script src="{{ url('assets/themes/default/js/chat.js') }}"></script>
@endpush
