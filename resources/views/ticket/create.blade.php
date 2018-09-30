@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body panel-padding">

	<legend>
		<h3 class="text-muted">Ticket: Create</h3>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('ticket') }}">Ticket</a></li>
		<li class="active">Create</li>
	</ul>
		      
	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'route' => 'ticket.store', 'class' => 'form-horizontal', 'id' => 'ticket-form']) }}

		<div class="form-group">
			<div class="col-sm-3">
				{{ Form::label('subject', 'Subject') }}
			</div>
			<div class="col-sm-9">
				{{ Form::text('subject', old('subject'), [
					'class' => 'form-control',
					'placeholder' => 'Input a unique label for ticket'
				]) }}

				<p class="text-danger" style="font-size: 12px;">*{{ __('label.required_field') }}</p>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3">
				<label>Type:</label>
			</div>
			<div class="col-sm-9">
				<select name="type" class="form-control">
				@if( !empty( $types))
					@foreach( $types as $key => $value )
					<option value="{{ $key }}" {{ (old('type') == $key) ? "selected" : "" }}>{{ $key }}</option>
					@endforeach
				@endif
				</select>
				
				<p class="text-danger" style="font-size: 12px;">*{{ __('label.required_field') }}</p>
			</div>
		</div>

		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2 )

		<div class="form-group">
			<div class="col-sm-3">
				{{ Form::label('author', 'Author') }}
			</div>
			<div class="col-sm-9">
				{{ Form::text('author', old('author'), [
					'class' => 'form-control',
					'placeholder' => Auth::user()->full_name
				]) }}

				<p class="text-muted text-warning" style="font-size: 12px;">
					Leave this field blank if you're the <span>author</span>.
				</p>
			</div>
		</div>

		@endif
		
		<div class="form-group">
			<div class="col-sm-3">
				{{ Form::label('tag', 'Tag (Optional)') }}
			</div>
			<div class="col-sm-9">
				{{ Form::text('tag', old('tag'), [
					'id' => 'tag',
					'class' => 'form-control',
					'placeholder' => 'Property Number, Room Name, Workstation Name'
				]) }}

				<p class="text-muted text-info" style="font-size: 12px;">This field is for identifying the equipment, room, or workstation linked to this ticket.</p>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				{{ Form::label('details', 'Details') }}
	
				<p class="text-muted" style="font-size: 12px;">
					This field is required to further explain the details of the ticket
				</p>
	
				{{ Form::textarea('details', old('details'), [
					'class' => 'form-control',
					'placeholder' => 'Enter ticket details here...'
				]) }}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12">
				{{ Form::submit('Create', [
					'class'=> 'btn btn-lg btn-block btn-md btn-primary'
				]) }}
			</div>
		</div>

	{{ Form::close() }}
</div>
@stop