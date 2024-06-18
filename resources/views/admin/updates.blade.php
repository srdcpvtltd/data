@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ( setting( 'purchase_code' ) == '' ||  setting( 'cc_token' ) == '' )
                <div class="alert alert-warning">To get automatic updates from ChargePanda you need to save your Purchase code and CodeCanyon.net API key in <a href="{{route('ch-admin.settings.show', ['update'])}}">Settings -> Update</a></div>
            @else
            <p>Last checked on {{ setting('update_last_check') == '' ? 'Never' : date('M d, Y h:i:s A', setting('update_last_check')) }}.</p>
                <form action="" method="post">
                    {{ csrf_field() }}
                    <input type="submit" name="check_updates" value="Check Again" class="btn btn-default">
                </form>

                @if ( isUpToDate() == false )
                    <h3>An updated version of ChargePanda is available.</h3>
                    <p>You can update to ChargePanda latest version automatically:</p>


                    @if ( session('update_status') != 'downloaded' )

                        <form action="" method="post">
                            {{ csrf_field() }}
                            <input type="submit" name="download_now" value="Prepare Download files" class="btn btn-primary">
                        </form>

                    @else

                        <form action="" method="post">
                            {{ csrf_field() }}
                            <input type="submit" name="update_now" value="Update now" class="btn btn-primary">
                        </form>

                    @endif
                    <p>While your site is being updated, it will be in maintenance mode. As soon as your updates are complete, your site will return to normal.</p>

                @else

                    <h3>You have the latest version of ChargePanda.</h3>

                @endif
            @endif
        </div>
    </div>
@endsection
