@extends('layouts.master-blue')
@section('content')
{{ Form::open(array('class' => 'form-horizontal','method'=>'put','route'=>array('type.update',$itemtype->id),'id'=>'itemTypeForm')) }}
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-md-offset-3 col-md-6">
      <div class="panel panel-body ">
        <legend><h3 class="text-muted">Item Types</h3></legend>
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
                <a href="{{ url('item/type') }}">Item Type</a>
            </li>
            <li>{{ $itemtype->id }}</li>
            <li class="active">Edit</li>
        </ol>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('category','Category') }}
            {{ Form::select('category',$category,Input::old('category'),[
              'class'=>'form-control'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('name','Item name') }}
            {{ Form::text('name',$itemtype->name,[
              'id' => 'name',
              'class'=>'form-control',
              'placeholder'=>'Item name'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('description','Description') }}
            {{ Form::textarea('description',$itemtype->description,[
              'id' => 'description',
              'class'=>'form-control',
              'placeholder'=>'Description'
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
