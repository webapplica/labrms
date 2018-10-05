@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body panel-padding">

	<legend>
        <h3 class="text-muted pull-left">Item {{ $item->local_id }}: Add Activity</h3>
        <span class="pull-right label label-info label-lg">{{ $currentDate }}</span>
        <div class="clearfix"></div>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('inventory') }}">Item</a></li>
		<li><a href="{{ url('item/' . $item->id) }}">{{ $item->local_id }}</a></li>
		<li class="">Activity</li>
		<li class="active">Add</li>
	</ul>
		      
	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'url' => url('item/' . $item->id . '/activity/add'),  'id' => 'item-form']) }}

		<div class="form-group">
			{{ Form::label('details', 'Details') }}

			<p class="text-muted" style="font-size: 12px;">
				Enter specific details of the activity done to the item.
			</p>

			{{ Form::textarea('details', old('details'), [
				'class' => 'form-control',
				'placeholder' => 'Enter details here...'
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