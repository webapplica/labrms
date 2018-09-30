@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body panel-padding">

	<legend>
		<h3 class="text-muted">Ticket {{ $ticket->title }}: Action</h3>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('ticket') }}">Ticket</a></li>
		<li><a href="{{ url('ticket/' . $ticket->id) }}">{{ $ticket->title }}</a></li>
		<li class="active">Action</li>
	</ul>
		      
	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'url' => url('ticket/' . $ticket->id . '/transfer'),  'id' => 'ticket-form']) }}

		<div class="form-group">
			{{ Form::label('staff', 'Staff') }}
			<select
				name="staff"
				class="form-control"
				>
				<option>None</option>

				@foreach($users as $user)
					<option 
						value="{{ $user->id }}">
					@if($user->id == old('staff'))
						selected
					@endif
						{{ $user->firstname_first }}
					</option>

				@endforeach
		</select>

			<p class="text-info" style="font-size: 12px;">Set this field to whom you want to transfer the ticket to</p>
		</div>

		<div class="form-group">
			{{ Form::label('subject', 'Subject') }}
			{{ Form::text('subject', old('subject'), [
				'class' => 'form-control',
				'placeholder' => 'Input the best title for the action done'
			]) }}

			<p class="text-danger" style="font-size: 12px;">*{{ __('label.required_field') }}</p>
		</div>

		<div class="form-group">
			{{ Form::label('details', 'Details') }}

			<p class="text-muted" style="font-size: 12px;">
				This field is required to further explain the details of the ticket
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