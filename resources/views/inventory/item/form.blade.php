<div id="alert-existing"></div>
<!-- item name -->
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('brand','Brand') }}
	{{ Form::text('brand',isset($brand) ? $brand : old('brand'),[
		'class' => 'form-control',
		'placeholder' => 'Brand',
		'id' => 'brand'
	]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('model','Model') }}
	{{ Form::text('model',isset($model) ? $model : old('model'),[
		'class' => 'form-control',
		'placeholder' => 'Model',
		'id' => 'model'
	]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('itemtype','Type') }}
	{{ Form::select('itemtype', $itemtypes, isset($itemtype) ? $itemtype : old('itemtype'),[
		'class' => 'form-control',
		'id' => 'itemtype'
	]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('details','Other Details') }}
	{{ Form::textarea('details',old('details'),[
		'class' => 'form-control',
		'placeholder' => 'Item Details',
		'id' => 'details',
		'rows' => 4    
	]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('unit','Unit') }}
	{{ Form::select('unit',$units,old('unit'),[
		'class' => 'form-control'
	]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-12">
	{{ Form::label('quantity','Quantity') }}
	{{ Form::number('quantity',old('quantity'),[
		'class' => 'form-control',
		'placeholder' => 'Quantity'
	]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-12">
	<input type="checkbox" id="redirect-profiling" name="redirect-profiling" checked />
	<span class="text-muted" style="font-size:12;">Proceed to Profiling</span>
	</div>
</div>

<script>
	
	$(document).ready(function(){

		$('#brand').autocomplete({
			source: "{{ url('get/inventory/item/brand') }}"
		})

		$('#model').autocomplete({
			source: "{{ url('get/inventory/item/model') }}"
		})
	})

</script>