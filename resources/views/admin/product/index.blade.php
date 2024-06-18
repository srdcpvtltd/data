@extends('admin.layouts.app')


@section('title', 'Products')

@section('content')
    <div class="col-md-12">
        <h4 class="c-grey-900 mT-10 mB-30">{{$title}} <a class="btn btn-danger" href="{{route('ch-admin.product.create')}}"><i class="ti-plus"></i></a></h4>
    </div>
    <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20 mB-20">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{url('assets/backend/js/datatables.js')}}"></script>
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

@push('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
@endpush
