<div class="form-group">
    {{ Form::label('quantity', 'Quantity') }}
    {{ Form::number('quantity', old('quantity'),[
        'id' => 'quantity',
        'class' => 'form-control',
        'placeholder' => 'Insert the quantity of items to be released',
        'min' => '1',
        'required'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('details','Details (Explanation)') }}

    <p class="text-muted" style="font-size: 12px;">
        Include an explanation the purpose of releasing items.
    </p>

	{{ Form::textarea('details', old('details'), [
		'class' => 'form-control',
		'placeholder' => 'Insert details for releasing the item...',
		'id' => 'details',
		'rows' => 8    
	]) }}
</div>