@extends('admin.layouts.master')


@section('content')


<div class="row">
@foreach( $media as $file )

  <div class="col-xs-4 col-md-2">
    <a href="{{ route('ch-admin.media.edit', [$file->id]) }}" class="thumbnail file-thumbnail">
      <img src="{{ $file->ThumbnailUrl }}" alt="{{ $file->title }}">
      @if ( ! is_image( $file->mine_type ) ) 
        <div class="filename">{{ $file->filename }}</div>
      @endif
    </a>
  </div>

@endforeach

</div>

<div class="row">
    <div class="col-lg-12">
        {!! $media->render() !!}
    </div>
</div>

@endsection