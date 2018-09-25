@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-offset-3 col-md-6 panel panel-body ">
    <legend><h3 class="text-muted">Item Types</h3></legend>
    
    @include('errors.alert')

    <ol class="breadcrumb">
        <li>
            <a href="{{ url('item/type') }}">Item Type</a>
        </li>
        <li>{{ $type->name }}</li>
        <li class="active">Edit</li>
    </ol>

    {{ Form::open([
      'method' => 'put',
      'url' => url('item/type/' . $type->id),
      'id' => 'item-type-form'
    ]) }}

    @include('maintenance.item.type.form')

    <div class="form-group">
        <button class="btn btn-primary btn-flat btn-block btn-lg" type="submit" style="padding:10px;">
            Update
        </button>
    </div>

    {{ Form::close() }}
</div>
@stop
