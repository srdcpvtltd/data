@extends('admin.layouts.app')


@section('title', 'Languages')

@section('content')
    <nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                @foreach($groups as $key => $group)
                        <li class="nav-item">
                            <a class="nav-link {!! (($key == $active_group) || ($key == '*' && $active_group == 'general')) ? 'active' : '' !!}" href="{{route('ch-admin.phrases.edit', [$language->id, $key == '*' ? 'general' : $key])}}">{{$group}}</a>
                        </li>
                    @endforeach
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>


    <form class="bgc-white bd bdrs-3 p-20 mB-20" action="{{route('ch-admin.phrases.update', [$language->id, $active_group])}}" method="post">
        {{method_field('PUT')}}
        {{csrf_field()}}

        <div class="row">
            <div class="col-md-7">
                <h3>{{$groups[$active_group]}} Phrases</h3>
            </div>
            <div class="col-md-5"><button type="submit" class="btn btn-primary float-end btn-sm text-white">Save Phrases</button></div>
        </div>
        @forelse($phrases as $phrase)
            <div class="mb-3 row">
                <div class="col-sm-12">
                    @if(str_contains($phrase->key, '|'))
                        <label for="phrase[]" class="control-label">{{ucfirst(explode('|', $phrase->key)[0])}}</label>
                    @endif

                    @if($phrase->group == 'email')
                        <label for="phrases[{{$phrase->key}}]" class="control-label">{{ucwords(implode(' email ', explode('.', $phrase->key)))}}</label>
                    @endif

                    @if($phrase->group == 'validation')
                        <label for="phrases[{{$phrase->key}}]" class="control-label">{{$phrase->key}}</label>
                    @endif

                    <textarea class="form-control" id="phrases[{{$phrase->key}}]"
                              name="phrases[{{$phrase->key}}]">{{$phrase->value}}</textarea>
                    @if (strpos($phrase->value, ':') !== false)
                        <p class="help-block text-muted"><strong>Note:</strong> Don't change or modify the words starting with colon (<code>:</code>)</p>
                    @endif
                </div>
            </div>
        @empty
            <p>No Phrases Found.</p>
        @endforelse

        @if($phrases->count() > 0)
            <div class="form-group row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary text-white">Save Phrases</button>
                </div>
            </div>
        @endif
    </form>
@endsection
