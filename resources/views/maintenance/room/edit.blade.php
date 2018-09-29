@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body" style="padding: 35px 25px 25px 25px;">
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

        @include('maintenance.room.partials.form')

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
