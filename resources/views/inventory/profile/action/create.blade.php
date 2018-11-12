@extends('layouts.app')

@section('content')
<div 
	class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body">

	<legend>
		<h3 class="text-muted">Item: {{ $item->local_id }}</h3>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('item') }}">Item</a></li>
		<li><a href="{{ url('item/' . $item->id) }}">{{ $item->local_id }}</a></li>
		<li class="active">Action</li>
	</ul>
		      
	@include('errors.alert')

    {{ Form::open(['method' => 'post', 'url' => url('item/' . $item->id . '/activity/add'),  'id' => 'item-form']) }}
    
        <div class="form-group">
            <input 
                type="checkbox"
                name="maintenance"
                {{ old('maintenance') || $item->isUnderMaintenance() ? 'checked' : '' }}
                /> {{ __('Undermaintenance?') }}

        </div>

		<div class="form-group">
			{{ Form::label('subject', 'Subject') }}
			{{ Form::text('subject', old('subject'), [
				'class' => 'form-control',
				'placeholder' => 'Input a subject for the action done'
			]) }}

			<p class="text-danger" style="font-size: 12px;">*{{ __('label.required_field') }}</p>
		</div>

		<div class="form-group">
			{{ Form::label('details', 'Details') }}

			<p class="text-muted" style="font-size: 12px;">
				This field is required to further explain the details of the action
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