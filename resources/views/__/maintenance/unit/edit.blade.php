@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body">
    <legend><h3 class="text-muted">Unit: Update</h3></legend>

    <ol class="breadcrumb">
      <li><a href="{{ url('unit') }}">Unit</a></li>
      <li class="active">Update</li>
    </ol>

    @include('alert.errors')

    {{ Form::open([
        'method' => 'put',
        'route' => array('unit.update', $unit->id),
        'id' => 'unit-form'
    ]) }}

        @include('maintenance.unit.partials.form')

        <div class="form-group">
            <button id="submit" class="btn btn-md btn-primary" type="submit">
                Update
            </button>

            <a id="cancel-btn" class="btn btn-md btn-default" type="button" href="{{ url('maintenance/unit') }}" >
                Cancel
            </a>
        </div>

    {{ Form::close() }}
</div>
@stop
