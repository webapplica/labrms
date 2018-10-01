<div class="form-group">
	{{ Form::label('brand','Brand') }}
	{{ Form::text('brand', isset($inventory->brand) ? $inventory->brand : old('brand'), [
		'class' => 'form-control',
		'placeholder' => 'Brand',
		'id' => 'brand'
	]) }}
</div>

<div class="form-group">
	{{ Form::label('model','Model') }}
	{{ Form::text('model', isset($inventory->model) ? $inventory->model : old('model'), [
		'class' => 'form-control',
		'placeholder' => 'Model',
		'id' => 'model'
	]) }}
</div>

<div class="form-group">
	{{ Form::label('type','Type') }}
	{{ Form::select('type', $types, isset($inventory->type) ? $inventory->type : old('type'), [
		'class' => 'form-control',
		'id' => 'type'
	]) }}
</div>

<div class="form-group">
	{{ Form::label('details','Other Details') }}
	{{ Form::textarea('details', isset($inventory->details) ? $inventory->details : old('details'), [
		'class' => 'form-control',
		'placeholder' => 'Insert other information here which cannot be placed in the existing forms...',
		'id' => 'details',
		'rows' => 4    
	]) }}
</div>

<div class="form-group">
	{{ Form::label('unit','Unit') }}
	{{ Form::select('unit', $units,  isset($inventory->unit) ? $inventory->unit : old('unit'), [
		'class' => 'form-control'
	]) }}
</div>

<div class="form-group">
	{{ Form::label('quantity','Quantity') }}
	{{ Form::number('quantity', old('quantity'), [
		'class' => 'form-control',
		'placeholder' => 'Quantity'
	]) }}
</div>