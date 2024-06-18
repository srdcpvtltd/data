@extends('admin.layouts.app')


@section('title', $title)

@section('content')
    @livewire('admin.message.chat-box', ['order' => $order])
@endsection


@push('ch_footer')
    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/backend/js/media.js') }}"></script>
@endpush
