<div class="form-group">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', isset($category->name) ? $category->name : old('name'), [
        'required',
        'class' => 'form-control',
        'placeholder' => 'Name'
    ]) }}
</div>