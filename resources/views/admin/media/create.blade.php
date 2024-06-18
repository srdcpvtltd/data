@extends('admin.layouts.master')

@section('title', 'Upload Media')

@section('content')
    @include('admin.media.upload')
@endsection
@push('ch_footer')
    <script src="{{ url('assets/backend/js/vendors/dropzone.min.js') }}"></script>
    <script src="{{ url('assets/backend/js/media.js') }}"></script>
@endpush

