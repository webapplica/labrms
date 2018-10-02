<div class="form-group">
    <label for="inventory-id">
        Inventory	
    </label>
    {{ Form::text('inventory_name', $inventory->summarized_name, [
        'class' => 'form-control',
        'placeholder' => "$inventory->brand-$inventory->model" ,
        'readonly',
        'style' => 'background-color: white;'
    ]) }}
    <input type="hidden" value="{{ $inventory->id }}" name="inventory" />
</div>

<div class="form-group">
    {{ Form::label('receipt_id', 'Acknowledgement Receipt') }}
    {{ Form::select('receipt_id', $receipts, old('receipt_id'), [
        'class' => 'form-control readonly-white',
        'style' => 'background-color:white;'
    ]) }}
</div>

<div class="form-group" style="padding-bottom: 10px;">
    {{ Form::label('location','Location') }}
    <select name="location" id="location" class="form-control">
        @foreach($locations as $key=>$value)
            <option {{ (old('location') == $key) ? 'selected=selected' : ($value == 'Server') ? 'selected=selected' : null }} value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
    <p class="text-muted pull-right" style="font-size: 10px;"><span class="text-danger">Note:</span> The Default Storage Location is <strong>Server Room</strong></p>
</div>