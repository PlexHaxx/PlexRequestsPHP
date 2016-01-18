@extends('layouts.app')

@section('content')

<h4>Welcome to PlexRequests! <small>request Movies and TV shows, and report incorrect entries</small></h4>
@if ( ! Session::has('user'))
	<p>Please enter your Plex username below</p>
	<form method="POST" action="" class="form form-horizontal">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="form-group">
			<div class="col-sm-4">
				<input type="text" class="form-control" name="plex_username" id="plex_username">
			</div>
		</div>
	</form>
@else
	Hey {{ Session::get('user') }}!


@endif
@endsection
