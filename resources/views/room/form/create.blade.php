{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <ul style='margin-left: 10px;'>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
{{ Form::open(array('method'=>'post','route'=>'room.store','class' => 'form-horizontal','id'=>'roomForm')) }}
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('name','Room Name') }}
    {{ Form::text('name',Input::old('name'),[
      'required',
      'class'=>'form-control',
      'placeholder'=>'Room Name'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('category','Room Category') }}
    {{ Form::select('category[]',isset($category) ? $category : ['Empty list'=>'Empty list'],Input::old('category'),[
      'id' => 'category',
      'class'=>'form-control',
      'multiple' => 'multiple'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::label('description','Description') }}
    {{ Form::textarea('description',Input::old('description'),[
      'required',
      'class'=>'form-control',
      'placeholder'=>'Room Description'
    ]) }}
  </div>
</div>
<div class="form-group">
  <div class="col-md-12">
    {{ Form::submit('Create',[
      'class'=>'btn btn-lg btn-primary btn-block',
      'name' => 'create',
      'id' => 'create'
    ]) }}
  </div>
</div>
{{ Form::close() }}
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
<script>
    $('#category').selectpicker('refresh');
    $('#category').selectpicker();
</script>
