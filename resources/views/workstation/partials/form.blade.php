

<div class="form-group">
    {{ Form::label('os','OS License Key') }}

    {{ Form::text('os', isset($workstation->oskey) ? $workstation->oskey : old('os'), [
        'id' => 'os',
        'class' => 'form-control',
        'placeholder' => 'OS License Key'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('systemunit','System Unit') }}
    {{ Form::text('systemunit', old('systemunit'), [
        'id' => 'systemunit',
        'class' => 'form-control has-autocomplete',
        'data-type' => 'system-unit',
        'data-url' => url('item?type="System Unit"&&column=local_id'),
        'placeholder' => 'This field accepts a local id for input',
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('monitor', 'Monitor') }}
    {{ Form::text('monitor',old('monitor'), [
        'id' => 'monitor',
        'class' => 'form-control has-autocomplete',
        'data-type' => 'monitor',
        'data-url' => url('item?type=Monitor&&column=local_id'),
        'placeholder' => 'This field accepts a local id for input',
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('avr', 'AVR') }}
    {{ Form::text('avr',old('avr'), [
        'id' => 'avr',
        'class' => 'form-control has-autocomplete',
        'data-type' => 'avr',
        'data-url' => url('item?type=Avr&&column=local_id'),
        'placeholder' => 'This field accepts a local id for input.'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('keyboard','Keyboard') }}
    {{ Form::text('keyboard', old('keyboard'), [
        'id' => 'keyboard',
        'class' => 'form-control has-autocomplete',
        'data-type' => 'keyboard',
        'data-url' => url('item?type=Keyboard&&column=local_id'),
        'placeholder' => 'This field accepts a local id for input'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('mouse', 'Mouse: ') }}
    {{ Form::checkbox('mouse', old('mouse'), [
        'id' => 'mouse',
    ]) }}
</div>

<div class="form-group">
    <button 
        class="btn btn-primary btn-lg btn-block" 
        name="create" 
        type="submit">
        <span class="glyphicon glyphicon-check"></span> Add
    </button>
</div>