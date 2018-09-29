@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body" style="padding: 35px 25px 25px 25px;">
    <legend>
      <h3 class="text-muted">Room Category Update</h3>
    </legend>

    <ol class="breadcrumb">
      <li><a href="{{ url('room') }}">Room</a></li>
      <li><a href="{{ url('room/category') }}">Category</a></li>
      <li class="active">{{ $category->id }}</li>
      <li class="active">Update</li>
    </ol>

    @include('errors.alert')

    {{ Form::open(['method' => 'put', 'url' => url('room/category/'. $category->id), ]) }}

        @include('maintenance.room.category.partials.form')

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
