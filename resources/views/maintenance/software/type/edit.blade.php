@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-offset-3 col-md-6 panel panel-body ">
    <legend>
        <h3 class="text-muted">Software Type: {{ $type->type }}</h3>
    </legend>
    
    @include('errors.alert')

    <ol class="breadcrumb">
        <li><a href="{{ url('software') }}">Software</a></li>
        <li><a href="{{ url('software/type') }}">Type</a> </li>
        <li>{{ $type->type }}</li>
        <li class="active">Edit</li>
    </ol>

    {{ Form::open([
      'method' => 'put',
      'url' => url('software/type/' . $type->id),
      'id' => 'software-type-form'
    ]) }}

        @include('maintenance.software.type.partials.form')

        <div class="form-group">
            <button class="btn btn-primary btn-flat btn-block btn-lg" type="submit" style="padding:10px;">
                Update
            </button>
        </div>

    {{ Form::close() }}
</div>
@stop
