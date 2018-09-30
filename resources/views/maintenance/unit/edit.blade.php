@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body">
    <legend><h3 class="text-muted">Unit: Update</h3></legend>

    <ol class="breadcrumb">
      <li><a href="{{ url('unit') }}">Unit</a></li>
      <li class="active">Update</li>
    </ol>

    @include('errors.alert')

    {{ Form::open([
        'method' => 'put',
        'url' => url('unit/'. $unit->id),
        'id' => 'unit-form'
    ]) }}

        @include('maintenance.unit.partials.form')

        <div class="form-group">
            <button id="submit" class="btn btn-md btn-primary" type="submit">
                Update
            </button>

            <a id="cancel-btn" class="btn btn-md btn-default" type="button" href="{{ url('unit') }}" >
                Cancel
            </a>
        </div>

    {{ Form::close() }}
</div>
@stop
