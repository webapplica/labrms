@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body panel-padding">

	<legend>
		<h3 class="text-muted">Reservation #{{ $reservation->id }}</h3>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('reservation') }}">Reservation</a></li>
		<li><a href="{{ url('reservation/' . $reservation->id) }}">{{ $reservation->id }}</a></li>
		<li class="active">Disapprove</li>
	</ul>
		      
	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'url' => url('reservation/' . $reservation->id . '/disapprove'),  'id' => 'reservation-form']) }}

		<div class="form-group">
			{{ Form::label('remarks', 'Additional Remarks') }}

			{{ Form::textarea('remarks', old('remarks'), [
				'class' => 'form-control',
				'placeholder' => 'Input text here...'
			]) }}
		</div>

		<div class="form-group">
			{{ Form::submit('Submit', [
				'class'=> 'btn btn-lg btn-block btn-md btn-primary'
			]) }}
		</div>

	{{ Form::close() }}
</div>
@stop