@extends('themes.default.app', ['title' => $title])

@section('content')

    <div class="container page-ctn">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h1>{{$title}}</h1>
                {!! $content !!}
            </div>
        </div>
    </div>

@endsection
