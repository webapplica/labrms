<div class="form-group">
    {{ Form::label('name','Name') }}
    {{ Form::text('name', isset($type->name) ? $type->name : old('name'), [
      'class' => 'form-control',
      'placeholder' => 'Name'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('description','Description (Optional)') }}
    {{ Form::textarea('description', isset($type->description) ? $type->description : old('description'), [
      'class' => 'form-control',
      'placeholder' => 'Description'
    ]) }}
</div>
