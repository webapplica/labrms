<div class="form-group">
    {{ Form::label('title','Title') }}
    {{ Form::text('title', isset($purpose->title) ? $purpose->title : old('title'), [
      'class' => 'form-control',
      'placeholder' => 'Title'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('description','Description (Optional)') }}
    {{ Form::textarea('description', isset($purpose->description) ? $purpose->description : old('description'), [
      'class' => 'form-control',
      'placeholder' => 'Description'
    ]) }}
</div>
