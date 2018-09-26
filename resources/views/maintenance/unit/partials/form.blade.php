<div class="form-group">
    {{ Form::label('name','Name') }}
    {{ Form::text('name', isset($unit->name) ? $unit->name : old('name'), [
      'class' => 'form-control',
      'placeholder' => 'Name'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('abbreviation','Abbreviation') }}
    {{ Form::text('abbreviation', isset($unit->abbreviation) ? $unit->abbreviation : old('abbreviation'), [
      'class' => 'form-control',
      'placeholder' => 'abbreviation'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('description','Description') }}
    {{ Form::text('description', isset($unit->description) ? $unit->description : old('description'), [
      'class' => 'form-control',
      'placeholder' => 'Description'
    ]) }}
</div>