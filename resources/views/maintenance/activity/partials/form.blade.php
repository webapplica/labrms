
<!-- Title -->
<div class="form-group">

	<div class="col-sm-12">
	{{ Form::label('type','Maintenance Type') }}
	</div>

	<div class="col-sm-6">
	  <input 
	  	type="radio" 
	  	id="corrective" 
	  	name="type" 
	  	value='corrective' 

		@if(isset($activity->type) && $activity->type == 'Corrective')
	  	checked
	  	@endif 

	  	/> Corrective
	</div>
	<div class="col-sm-6">
	  <input 
	  	type="radio" 
	  	id="preventive" 
	  	name="type" 
	  	value='preventive'

		@if(isset($activity->type) && $activity->type == 'Preventive')
	  	checked
	  	@endif 

	  	/> Preventive
	</div>
</div>

@include('maintenance.activity.partials.maintenance_definition')

<!-- Title -->
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('activity','Activity Title') }}
	{{ Form::text('activity', isset($activity->name) ? $activity->name : old('name'),[
		'class'=>'form-control',
		'placeholder'=>'Title of the activity done'
	]) }}
	</div>
</div>

<!-- Details -->
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('details','Details') }}
	{{ Form::textarea('details', isset($activity->details) ? $activity->details : old('details'),[
		'class'=>'form-control',
		'placeholder'=>'Description of the maintenance activity done'
	]) }}
	</div>
</div>