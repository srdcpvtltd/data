@extends('admin.layouts.master')


@section('title', 'Edit Media')

@section('content')
<div class="row">



    <div class="col-md-6">



        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $file->filename }}</h3>
            </div>
            <!-- /.box-header -->        
            {!! Form::model( $file, [ 'method' => 'PATCH', 'route' => [ 'ch-admin.media.update', $file->id ] ] ) !!}
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('file_url', 'File URL:') !!}
                    {!! Form::text('file_url', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('title', 'Title:') !!}
                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                </div>


                @if ( is_image($file->mime_type) ) 

                <div class="form-group">
                    {!! Form::label('alt_text', 'Alt Text:') !!}
                    {!! Form::text('media_meta[alt_text]', null, ['class' => 'form-control']) !!}
                </div>
                
                <img class="img-responsive" src="{{ $file->file_url }}" alt="{{ $file->alt_text }}">

                @endif
            </div>
            <div class="box-footer">

                {!! Form::submit('Update file', ['class' => 'btn btn-primary']) !!}

            </div>

            {!! Form::close() !!}    
        </div>
    </div>

    <div class="col-lg-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">File Info</h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File name:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $file->filename }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File type:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $file->mime_type }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File size:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $file->size }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Uploaded by:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $file->user->name }}</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>
@endsection

