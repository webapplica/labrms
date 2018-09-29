<div class="form-group">
    {{ Form::label('name', 'Room Name') }}
    {{ Form::text('name', isset($room->name) ? $room->name : old('name'), [
        'required',
        'class' => 'form-control',
        'placeholder' => 'Room Name'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('category', 'Room Category') }}
    <select id="category" class="form-control" multiple name="category[]">
        @foreach($categories as $key => $value)
        <option 
            value="{{ $key }}"
            
            @if((isset($room->categories) && in_array($key, $room->includeCategoriesOnColumn('id')->toArray())) || (in_array($key, old('category') ?? [])))
                selected
            @endif

            >{{ $value }}</option>
        @endforeach
    </select>

</div>

<div class="form-group">
    {{ Form::label('description', 'Description') }}
    {{ Form::textarea('description', isset($room->description) ? $room->description : old('description'), [
        'class' => 'form-control',
        'placeholder' => 'Room Description'
    ]) }}
</div>