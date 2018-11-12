@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6'>
		<div class="panel panel-body">
			<legend>
				<h3 class="text-primary">Workstation</h3>
			</legend>

			<ol class="breadcrumb">
				<li>
					<a href="{{ url('workstation') }}">Workstation</a>
				</li>
				<li>
					<a href="{{ url("workstation/$workstation->id/software") }}">{{ $workstation->name }}</a>
				</li>
				<li class="active">Assign</li>
			</ol>

			@include('workstation.partials.list')
			@include('errors.alert')

			<form 
				method="post"
				action="{{ url('workstation/' . $workstation->id . '/software/install') }}"
				style="margin-top: 25px;">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				<div class="form-group">
					{{ Form::label('software','Software') }}
					{{ Form::select('software', $softwares, old('software'), [
						'id' => 'software',
						'class' => 'form-control'
					]) }}
				</div>

				<div class="form-group">
					{{ Form::label('license','License Key') }}
					<p>{{ __('workstation.no_license_key_notice') }}</p>
					{{ Form::text('license', old('license'), [
						'id' => 'license',
						'class' => 'form-control',
						'placeholder' => 'Enter license key here...'
					]) }}
				</div>

				<div class="form-group">
					<button 
						type="submit" 
						class="btn btn-primary btn-lg btn-block">
						Install</button>
				</div>
			</form>
		</div>
	</div>
</div>
@stop