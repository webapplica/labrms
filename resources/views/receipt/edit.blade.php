@extends('layouts.master-blue')
@section('title')
Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <div class="col-md-12 panel panel-body " style="padding: 25px;padding-top: 10px;">
        <legend class='text-muted'><h3>Receipt</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('receipt') }}">Receipt</a>
          </li>
          <li>
            {{ $receipt->id }}
          </li>
          <li class="active">Edit</li>
        </ol>
        @include('errors.alert')
        {{ Form::open(array('method'=>'put','route'=>array('receipt.update', $receipt->id),'class' => 'form-horizontal')) }}
        <div class="form-group">
          <div class="col-md-12">
            @include('receipt.form')
            {{ Form::submit('Update',[
              'class'=>'btn btn-lg btn-primary btn-block',
              'name' => 'update'
            ]) }}
          </div>
        </div>
      {{ Form::close() }}
      </div>
    </div> <!-- centered  -->
  </div><!-- Row -->
</div><!-- Container -->
@stop
