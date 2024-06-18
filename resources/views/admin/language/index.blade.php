@extends('admin.layouts.app')


@section('title', 'Languages')

@section('content')

<div class="row">
    <div class="col-md-12">
        <h4 class="c-grey-900 mT-10 mB-30">{{$title}} <a class="btn btn-primary" href="{{route('ch-admin.language.create')}}">Add New</a> <a class="btn btn-primary" href="{{route('ch-admin.phrases.sync')}}">Sync / Update Translations</a></h4>
    </div>

    <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20 mB-20">


            <form class="mb-3">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input name="s" class="form-control pull-right" placeholder="Search" type="text" value="{{Request::input('s')}}">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Locale</th>
                        <th>Enabled</th>
                        <th>Default</th>
                        <th>Edit Phrases</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($languages as $language)
                    <tr>
                        <td>{{$language->id}}</td>
                        <td>{{$language->name}}</td>
                        <td>{{$language->locale}}</td>

                        <td>
                            {!! Form::open(['method' => 'PATCH', 'route' => ['ch-admin.language.update', $language->id]]) !!}
                                @if ($language->default)
                                    <span class="label label-warning">Default language.</span>
                                @elseif ($language->enabled)
                                    {!! Form::hidden('enabled', 0) !!}
                                    {!! Form::submit('Disable', ['class' => 'btn btn-sm btn-warning', $language->default == 1 ? 'disabled' : '']) !!}
                                @else
                                    {!! Form::hidden('enabled', 1) !!}
                                    {!! Form::submit('Enable', ['class' => 'btn btn-sm btn-info']) !!}
                                @endif
                            {!! Form::close() !!}
                        </td>

                        <td>

                            @if ($language->default == 1)
                                <span class="label label-success">Default Language</span>
                            @elseif ($language->enabled)
                                {!! Form::open(['method' => 'PATCH', 'route' => ['ch-admin.language.update', $language->id]]) !!}
                                    {!! Form::submit('Set as Default', ['class' => 'btn btn-sm btn-info']) !!}
                                    {!! Form::hidden('default', 1) !!}
                                {!! Form::close() !!}
                            @else
                                <span class="label label-warning">Language not enabled.</span>
                            @endif
                        </td>

                        <td><a href="{{route('ch-admin.phrases.edit', [$language->id])}}" class="btn btn-sm btn-info">Edit</a></td>
                        <td><a href="{{route('ch-admin.language.edit', [$language->id])}}" class="btn btn-sm btn-info">Edit</a></td>
                        <td>
                            @if ($language->id != 1)
                            {!! Form::open(['method' => 'DELETE', 'route' => ['ch-admin.language.destroy', $language->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-danger', 'onclick' => "return confirm('Are you sure you want to delete this language?');"]) !!}
                            {!! Form::close() !!}
                            @else
                                <span class="label label-warning">Cannot be deleted.</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No languages found.</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

            <div class="box-footer clearfix">

            </div>

    </div>
</div>
</div>
@endsection
