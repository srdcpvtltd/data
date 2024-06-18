@extends('admin.layouts.app')

@section('title', $title)

@section('content')

    <div class="row">

        {!! Form::model($settings, [ 'method' => 'PATCH', 'route' => [ 'ch-admin.settings.update', 1 ] ]) !!}

        <div class="col-md-12">
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                @include('admin.layouts.errors')

                @include('admin.settings.partials.'.$section)

                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary text-white">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection
