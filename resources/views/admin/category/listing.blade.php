<div class="col-md-6">
    <h4 class="c-grey-900 mT-10 mB-30">Categories</h4>
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
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach ( $categories as $id => $name )
                    <tr>
                        <th scope="row">{{ $id }}</th>
                        <td>{!! $name !!}</td>
                        <td><a href="{{ action( 'Admin\AdminCategoryController@edit', $id ) }}">Edit</a></td>
                        <td>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['ch-admin.category.destroy', $id]]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure you want to delete this item?');"]) !!}
                            {!! Form::close() !!}

                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>

        <div class="box-footer clearfix">
            {{$categories->setPath('category')->links()}}
        </div>

    </div>

</div>
