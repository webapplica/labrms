@extends('layouts.app')

@section('styles-include')
{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
@endsection

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body" style="padding: 25px;padding-top: 10px;">
    <legend>
      <h3 class="text-muted">Room Update</h3>
    </legend>

    <ol class="breadcrumb">
      <li><a href="{{ url('room') }}">Room</a></li>
      <li class="active">{{ $room->id }}</li>
      <li class="active">Update</li>
    </ol>

    @include('errors.alert')

    {{ Form::open(['method' => 'put', 'route' => array('room.update', $room->id), ]) }}

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
            {{ Form::select('category[]',['Empty list'=>'Empty list'], old('category'), [
              'id' => 'category',
              'class'=>'form-control',
              'multiple' => 'multiple'
            ]) }}
        </div>

        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', isset($room->description) ? $room->description : old('description'), [
              'class' => 'form-control',
              'placeholder' => 'Room Description'
            ]) }}
          </div>
        </div>

        <div class="form-group">
            {{ Form::submit('Update', [
              'class' => 'btn btn-lg btn-primary btn-block',
              'name' => 'create',
              'id' => 'create'
            ]) }}
        </div>

    {{ Form::close() }}
</div>
@stop

@section('scripts-include')
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
<script type="text/javascript">
    $(document).ready(function () {
        $('#category').selectpicker('val',[ 
            @foreach( explode(",",$room->category) as $category )
                {!! "'" . $category . "',"  !!}
            @endforeach
        ])
    });
</script>
@stop
