@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body " style="padding: 25px; padding-top: 10px;">
    <legend>
      <h3 class="text-muted">Faculty: Update</h3>
    </legend>

    <ol class="breadcrumb">
      <li><a href="{{ url('faculty') }}">Faculty</a></li>
      <li class="active">Update</li>
    </ol>

    @include('errors.alert')

    {{ Form::open(array('class' => 'form-horizontal', 'method' => 'put', 'route' => array('faculty.update', $faculty->id), 'id' => 'facultyForm')) }}

    @include('maintenance.faculty.partials.form')

    <button id="submit" class="btn btn-md btn-primary" type="submit">
      <span class="hidden-xs">Update</span>
    </button>

    <button id="cancel" class="btn btn-md btn-default" type="button" onClick="window.location.href='{{ url("maintenance/faculty") }}'" >
      <span class="hidden-xs">Cancel</span>
    </button>

    {{ Form::close() }}
</div>
@stop
