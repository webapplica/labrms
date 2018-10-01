<div id="alert-existing"></div>
<!-- item name -->
<div class="form-group">
	{{ Form::label('brand','Brand') }}
	{{ Form::text('brand', isset($brand) ? $brand : old('brand'), [
		'class' => 'form-control',
		'placeholder' => 'Brand',
		'id' => 'brand'
	]) }}
</div>
<div class="form-group">
	{{ Form::label('model','Model') }}
	{{ Form::text('model', isset($model) ? $model : old('model'), [
		'class' => 'form-control',
		'placeholder' => 'Model',
		'id' => 'model'
	]) }}
</div>
<div class="form-group">
	{{ Form::label('itemtype','Type') }}
	{{ Form::select('type', $types, isset($type) ? $type : old('type'), [
		'class' => 'form-control',
		'id' => 'type'
	]) }}
</div>
<div class="form-group">
	{{ Form::label('details','Other Details') }}
	{{ Form::textarea('details', old('details'), [
		'class' => 'form-control',
		'placeholder' => 'Insert other information here which cannot be placed in the existing forms...',
		'id' => 'details',
		'rows' => 4    
	]) }}
</div>
<div class="form-group">
	{{ Form::label('unit','Unit') }}
	{{ Form::select('unit', $units, old('unit'), [
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
<div class="form-group">
	<input type="checkbox" id="redirect-profiling" name="redirect-profiling" checked />
	<span class="text-muted" style="font-size: 12px;">Proceed to Profiling</span>
</div>
{{-- 
<script type="text/javascript">
	$(document).ready(function(){

		$('#brand').autocomplete({
			source: "{{ url('get/inventory/item/brand') }}"
		})

		$('#model').autocomplete({
			source: "{{ url('get/inventory/item/model') }}"
		})
	})
</script> --}}