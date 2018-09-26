@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-body">
    <legend><h3 class="text-muted">Unit: Create</h3></legend>

    <ol class="breadcrumb">
        <li>
            <a href="{{ url('unit') }}">Unit</a>
        </li>
        <li class="active">Create</li>
    </ol>

    @include('errors.alert')

    <form action="{{ url('maintenance/unit') }}" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="_method" value="PUT" />

        @include('maintenance.unit.partials.form')

        <div class="form-group">
            <button id="submit" class="btn btn-md btn-primary" type="submit">
                <span class="hidden-xs">Submit</span>
            </button>

            <button id="cancel" class="btn btn-md btn-default" type="button" onClick="window.location.href='{{ url("maintenance/unit") }}'" >
                <span class="hidden-xs">Cancel</span>
            </button>
        </div>

    </form>
</div>
@stop