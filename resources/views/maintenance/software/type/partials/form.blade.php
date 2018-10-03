<div class="form-group">
    {{ Form::label('type','Type') }}
    {{ Form::text('type', isset($type->type) ? $type->type : old('type'), [
      'class' => 'form-control',
      'placeholder' => 'type'
    ]) }}
</div>
