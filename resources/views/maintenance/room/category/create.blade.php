@extends('layouts.app')

@section('content')
<div class="col-sm-offset-3 col-sm-6 panel panel-body " style="padding: 35px 25px 25px 25px;">
    <legend>
        <h3 class="text-muted">Room Category</h3>
    </legend>

    <ol class="breadcrumb">
        <li><a href="{{ url('room') }}">Room</a></li>
        <li><a href="{{ url('room/category') }}">Category</a></li>
        <li class="active">Create</li>
    </ol>

    @include('errors.alert')

    {{ Form::open(array('method' => 'post', 'url' => url('room/category'))) }}
    
        @include('maintenance.room.category.partials.form')

        <div class="form-group">
            {{ Form::submit('Submit', [
                'class' => 'btn btn-lg btn-primary btn-block',
                'name' => 'create',
                'id' => 'create'
            ]) }}
        </div>

    {{ Form::close() }}
</div><!-- Container -->
@stop
