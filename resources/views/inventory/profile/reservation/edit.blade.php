@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body panel-padding">

	<legend>
        <h3 class="text-muted pull-left">Item {{ $item->local_id }}: Reservation</h3>
        <span class="pull-right label label-info label-lg">{{ $currentDate }}</span>
        <div class="clearfix"></div>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('inventory') }}">Item</a></li>
		<li><a href="{{ url('item/' . $item->id) }}">{{ $item->local_id }}</a></li>
		<li class="">Activity</li>
		<li class="active">Reservation</li>
	</ul>
		      
	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'url' => url('item/' . $item->id . '/activity/reservation-update'),  'id' => 'item-form']) }}
	
		<div class="form-group">
			{{ Form::label('reservation', 'Reservation Status') }}

			<p class="text-muted" style="font-size: 12px;">
				<input 
					type="checkbox" 
					name="reservation" 
					id="reservation" 
					{{ ($item->for_reservation) ? 'checked' : '' }}
					/> 
					Check the box beside this if you want to enable the item for reservation
			</p>

			
		</div>

		<div class="form-group">
			{{ Form::label('details', 'Details') }}

			<p class="text-muted" style="font-size: 12px;">
				Enter additional remarks as to why update the reservation toggle.
			</p>

			{{ Form::textarea('details', old('details'), [
				'class' => 'form-control',
				'placeholder' => 'Enter details here...',
				'required'
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