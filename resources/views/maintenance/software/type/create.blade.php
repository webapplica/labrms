@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-offset-3 col-md-6 panel panel-body">
    <legend>
        <h3 class="text-muted">Software Type: Create</h3>
    </legend>

    <ol class="breadcrumb">
        <li><a href="{{ url('software') }}">Software</a></li>
        <li><a href="{{ url('software/type') }}">Type</a> </li>
        <li class="active">Create</li>
    </ol>

    @include('errors.alert')

    {{ Form::open([
        'method' => 'post',
        'url' => url('software/type'),
        'id'=>'software-type-form',
    ]) }}

        @include('maintenance.software.type.partials.form')

        <div class="form-group">
            <button class="btn btn-primary btn-block btn-lg" type="submit">
                Submit
            </button>
        </div>

    {{ Form::close() }}
</div>
@stop
