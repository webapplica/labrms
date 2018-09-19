@extends('layouts.master-blue')
@section('title',isset($title) ? $title : config('app.name','LabRMS'))
@section('style')
<style>
  #page-body{
    display: none;
  }
</style>
@stop
@section('content')
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <div class="col-md-12 panel panel-body " style="padding: 25px;padding-top: 10px;">
        <legend><h3 class="text-muted">Room</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('room') }}">Room</a>
          </li>
          <li class="active">Create</li>
        </ol>
        @include('room.form.create')
        </div>
      </div> <!-- centered  -->
    </div><!-- Row -->
  </div><!-- Container -->
@stop
