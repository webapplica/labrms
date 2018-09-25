@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body">
    @include('alert.errors')

	<legend>
		<h3 style="color:#337ab7;">Room Log Form</h3>
	</legend>

	{{ Form::open([ 'class' => 'form-horizontal', 'method' => 'post', 'route' => 'room.log.store' ]) }}

	<div class="form-group">
		<div class="col-sm-3">
			{{ Form::label('room', 'Room Name') }}
		</div>

		<div class="col-sm-9">
			<select class="form-control" name="room" id="room">
				@forelse($rooms as $room)
					<option value="{{ $room->id }}">{{ $room->name }}</option>
				@empty
					<option>Empty list</option>
				@endforelse
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-3">
			{{ Form::label('name', 'Faculty-in-charge') }}
		</div>

		<div class="col-sm-9">
			{{ Form::text('name', old('name'), [
				'class' => 'form-control',
				'placeholder' => 'Faculty-in-charge'
			]) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-3">
			{{ Form::label('section', 'Section') }}
		</div>

		<div class="col-sm-9">
			{{ Form::text('section', old('section'), [
				'class' => 'form-control',
				'placeholder' => 'Section'
			]) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-3">
			{{ Form::label('units','No. of Working Units') }}
		</div>

		<div class="col-sm-9">
			{{ Form::number('units', Input::old('units'), [
				'class' => 'form-control',
				'placeholder' => 'No. of Working Units'
			]) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-3">
			{{ Form::label('time_start', 'Time started') }}
		</div>

		<div class="col-sm-9">
			{{ Form::text('time_start', old('time_start'), [
				'class' => 'form-control',
				'placeholder' => 'Hour : Min',
				'readonly',
				'id' => 'starttime'
			]) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-3">
			{{ Form::label('time_end','Time end') }}
		</div>

		<div class="col-sm-9">
			{{ Form::text('time_end', old('time_end'), [
				'class' => 'form-control',
				'placeholder' => 'Hour : Min',
				'readonly',
				'id' => 'endtime'
			]) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-6">
			{{ Form::submit('Create', [
				'class' => 'btn btn-primary btn-block'
			]) }}
		</div>

		<div class="col-sm-6">
			{{ Form::button('Cancel',[
				'class'=>'btn btn-info btn-block',
				'id' => 'cancel-btn'
			]) }}
		</div>
	</div>
	
	{{ Form::close() }}
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$('#cancel-btn').on('click', function () {
		window.location.href = '{{ route("dashboard.index") }}';
	});

	$('#starttime').timepicker({
		timeFormat: 'h:mm p',
		interval: 30,
		minTime: '7',
		maxTime: '7:00pm',
		defaultTime: '7:00am',
		startTime: '7:00am',
		dynamic: false,
		dropdown: true,
		scrollbar: true
	});  

	$('#endtime').timepicker({
	    timeFormat: 'h:mm p',
	    interval: 30,
	    minTime: '8',
	    maxTime: '9:00pm',
	    defaultTime: '8:00am',
	    startTime: '8:00am',
	    dynamic: false,
	    dropdown: true,
	    scrollbar: true
	});
</script>
@stop