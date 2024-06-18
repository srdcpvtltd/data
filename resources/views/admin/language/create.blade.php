@extends('admin.layouts.app')

@section('title', $title)

@section('content')

    <div class="row">


        {!! Form::open(['url' => 'ch-admin/language']) !!}

            {{method_field('POST')}}

            @include('admin.language.partials.form')

        {!! Form::close() !!}

    </div>

@endsection
