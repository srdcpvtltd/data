@extends('admin.layouts.app')

@section('title', $title)

@section('content')

<div class="row">


    {!! Form::open(['url' => 'ch-admin/user', 'class' => 'user-form']) !!}

    @include('admin.user.partials.form', ['submitLabel' => 'Create User'])

    {!! Form::close() !!}


</div>

@endsection
