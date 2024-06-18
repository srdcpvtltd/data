@extends('admin.layouts.app')


@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="c-grey-900 mT-10 mB-30">{{$title}} <a class="btn btn-primary" href="{{route('ch-admin.coupon.create')}}"><i class="ti-plus"></i></a></h4>
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
                            <th>Title</th>
                            <th>Used</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ( $coupons as $coupon )
                            <tr>
                                <th scope="row">{{ $coupon->id }}</th>
                                <td><a href="{{ route( 'ch-admin.coupon.edit', [$coupon->id]) }}">{{ $coupon->name }}</a></td>
                                <td>{{$coupon->used}}</td>
                                <td><a href="{{ route( 'ch-admin.coupon.edit', [$coupon->id]) }}">Edit</a></td>
                                <td>

                                    {!! Form::open(['method' => 'DELETE', 'route' => ['ch-admin.coupon.destroy', $coupon->id]]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure you want to delete this item?');"]) !!}
                                    {!! Form::close() !!}

                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="box-footer clearfix">
                    {{$coupons->links()}}
                </div>

            </div>
        </div>
    </div>
@endsection
