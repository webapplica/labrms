@extends('layouts.master-blue')
@section('content')
{{ Form::open(array('class' => 'form-horizontal','method'=>'put','route'=>array('subtype.update',$itemsubtype->id),'id'=>'itemTypeForm')) }}
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <div class="panel panel-body ">
        <legend><h3 class="text-muted">Item Sub Types</h3></legend>
        @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul style='margin-left: 10px;'>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('item/type') }}">Item Sub Type</a>
            </li>
            <li>{{ $itemsubtype->id }}</li>
            <li class="active">Edit</li>
        </ol>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('name','Name') }}
            {{ Form::text('name',isset($itemsubtype->name) ? $itemsubtype->name : Input::old('name'),[
              'class' => 'form-control',
              'id' => 'subtype-name',
              'placeholder' => 'Item Sub Type'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('name','Item Type') }}
            {{ Form::select('itemtype',$itemtypes,isset($itemsubtype->itemtype_id) ? $itemsubtype->itemtype_id : Input::old('itemtype'),[
              'class' => 'form-control',
              'id' => 'itemtype'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
                <button class="btn btn-primary btn-flat btn-block btn-lg" type="submit" style="padding:10px;">
                  <span class="glyphicon glyphicon-check"></span> <span>Update</span></button>
          </div>
        </div>
      </div> <!-- centered  -->
    </div>
  </div>
</div><!-- Container -->
{{ Form::close() }}
@stop
