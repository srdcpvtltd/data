<div class="media-row row">

    @if( $media->count() )    
        @foreach( $media as $file )

        <div class="col-xs-3 col-md-3">
            <a href="#" class="thumbnail" data-file-url="{{$file->file_url}}" data-attachment-id="{{$file->id}}">
                <img src="{{ $file->ThumbnailUrl }}" alt="{{ $file->title }}">
                <input type="checkbox" value="{{$file->id}}" name="attachments-check" @if ( $product_attachments->contains( $file->id ) ) checked="checked" @endif>
            </a>
        </div>

        @endforeach
    @else
    <div class="col-lg-12">
        <p>There are no files in media library. Please <a target="_blank" href="{{ route('ch-admin.media.create') }}">upload</a> the files.</p>
    </div>
    @endif
</div>
@if ($media->hasMorePages())
<a class="load-more-media" href="{{ $media->nextPageUrl() }}">Next</a>
@endif
