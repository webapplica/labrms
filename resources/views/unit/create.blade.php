@extends('layouts.master-blue')
@section('title')
Unit
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/datepicker.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  #page-body{
    display: none;
  }
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <div class="col-md-12 panel panel-body " style="padding: 25px;padding-top: 10px;">
        <legend><h3 class="text-muted">Unit</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('semester') }}">Unit</a>
          </li>
          <li class="active">Create</li>
        </ol>

        @include('errors.alert')

        {{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'unit.store','id'=>'unitForm')) }}
        <div class="" style="padding:10px;">
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('name','Name') }}
              {{ Form::text('name',Input::old('name'),[
                'class'=>'form-control',
                'placeholder'=>'Name'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('abbreviation','Abbreviation') }}
              {{ Form::text('abbreviation',Input::old('abbreviation'),[
                'class'=>'form-control',
                'placeholder'=>'abbreviation'
              ]) }}
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              {{ Form::label('description','Description') }}
              {{ Form::text('description',Input::old('description'),[
                'class'=>'form-control',
                'placeholder'=>'Description'
              ]) }}
            </div>
          </div>
          <div class="pull-right">
            <div class="btn-group">
              <button id="submit" class="btn btn-md btn-primary" type="submit">
                <span class="hidden-xs">Submit</span>
              </button>
            </div>
              <div class="btn-group">
                <button id="cancel" class="btn btn-md btn-default" type="button" onClick="window.location.href='{{ url("maintenance/unit") }}'" >
                  <span class="hidden-xs">Cancel</span>
                </button>
              </div>
          </div>
      </div> <!-- centered  -->
      {{ Form::close() }}
      </div>
    </div> <!-- centered  -->
  </div><!-- Row -->
</div><!-- Container -->
@stop