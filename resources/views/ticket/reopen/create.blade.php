@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body panel-padding">

	<legend>
		<h3 class="text-muted">Ticket {{ $ticket->title }}: Reopen</h3>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('ticket') }}">Ticket</a></li>
		<li><a href="{{ url('ticket/' . $ticket->id) }}">{{ $ticket->title }}</a></li>
		<li class="active">Reopen</li>
	</ul>
		      
	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'url' => url('ticket/' . $ticket->id . '/reopen'),  'id' => 'ticket-form']) }}

		<div class="form-group">
			{{ Form::label('subject', 'Subject') }}
			{{ Form::text('subject', old('subject'), [
				'class' => 'form-control',
				'placeholder' => 'Input the best title for the current action done'
			]) }}

			<p class="text-danger" style="font-size: 12px;">*{{ __('label.required_field') }}</p>
		</div>

		<div class="form-group">
			{{ Form::label('details', 'Details') }}

			<p class="text-muted" style="font-size: 12px;">
				A little bit of explanation why we need to reopen the ticket is required.
			</p>

			{{ Form::textarea('details', old('details'), [
				'class' => 'form-control',
				'placeholder' => 'Enter ticket details here...'
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