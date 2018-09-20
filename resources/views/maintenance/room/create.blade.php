@extends('layouts.app')

@section('body-content')
  <div class="col-sm-offset-3 col-sm-6 panel panel-body " style="padding: 25px; padding-top: 10px;">

      <legend>
        <h3 class="text-muted">Room</h3>
      </legend>

      <ol class="breadcrumb">
        <li>
          <a href="{{ url('room') }}">Room</a>
        </li>
        <li class="active">Create</li>
      </ol>

      @include('room.form.create')
      
</div><!-- Container -->
@stop
