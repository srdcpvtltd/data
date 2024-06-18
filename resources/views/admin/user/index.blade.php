@extends('admin.layouts.app')


@section('title', $title)

@section('content')

<div class="row">
    <div class="col-md-12">
        <h4 class="c-grey-900 mT-10 mB-30">{{$title}} <a class="btn btn-danger" href="{{route('ch-admin.user.create')}}"><i class="ti-plus"></i></a></h4>
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
                        <th>Email</th>
                        <th>Verified</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Registered</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ( $users as $user )
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td><a href="{{ route( 'ch-admin.user.edit', [$user->id]) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->hasVerifiedEmail() ? 'Yes' : 'No' }}</td>
                        <td>{{ ( $user->roles->first() !== null ) ? $user->roles->first()->display_name : 'N/A'  }}</td>
                        <td>{{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</td>
                        <td>{{ $user->created_at->diffForHumans() }}</td>
                        <td>
                            @can('delete', $user)
                            {!! Form::open(['method' => 'DELETE', 'route' => ['ch-admin.user.destroy', $user->id]]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure you want to delete this User?');"]) !!}
                            {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>

            <div class="box-footer clearfix">
                {{$users->links()}}
            </div>

    </div>
</div>
</div>
@endsection
