@extends('layouts.master-blue')
@section('title')
Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/bootstrap-clockpicker.min.css')) }}
{{ HTML::style(asset('css/datepicker.min.css')) }}
{{ HTML::style(asset('css/monthly.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
  #page-body{
    display: none;
  }

  .line-either-side {
    overflow: hidden;
    text-align: center;
  }
  .line-either-side:before,
  .line-either-side:after {
    background-color: #e5e5e5;
    content: "";
    display: inline-block;
    height: 1px;
    position: relative;
    vertical-align: middle;
    width: 50%;
  }
  .line-either-side:before {
    right: 0.5em;
    margin-left: -50%;
  }
  .line-either-side:after {
    left: 0.5em;
    margin-right: -50%;
  }
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
  <div class="row">
    <div class="col-sm-offset-3 col-sm-6">
      <div class="col-md-12 panel panel-body " style="padding: 25px;padding-top: 10px;">
        <legend><h3 class="text-muted">Create Schedule</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('schedule') }}">Schedule</a>
          </li>
          <li class="active">Create</li>
        </ol>
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
        {{ Form::open(array('method'=>'post','route'=>'scheduling.store','class' => 'form-horizontal')) }}
        <!-- date of use -->
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('dateofuse','Date of Use',[
              'data-language'=>"en"
            ]) }}
          </div>
          <div class="col-sm-9">
          {{ Form::text('dateofuse',Input::old('dateofuse'),[
            'id' => 'dateofuse',
            'class'=>'form-control',
            'placeholder'=>'MM | DD | YYYY',
            'readonly',
            'style'=>'background-color: #ffffff '
          ]) }}
          </div>
        </div>
          <div id="timerange">
            <div  class="form-group">
              <div class="col-sm-3">
                {{ Form::label('timestart','Time Start') }}
              </div>
              <div class="col-sm-9">
                {{ Form::text('timestart',Input::old('timestart'),[
                  'id' => 'starttime',
                  'class'=>'form-control',
                  'placeholder'=>'Time Start'
                ]) }}
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-3">
                {{ Form::label('timeend','Time End') }}
              </div>
              <div class="col-sm-9">
                {{ Form::text('timeend',Input::old('timeend'),[
                  'id' => 'endtime',
                  'class'=>'form-control time',
                  'placeholder'=>'Time End'
                ]) }}
              </div>
            </div>
          </div>
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('room','Room') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('room',$room,Input::old('room'),[
            'id' => 'room',
            'class' => 'form-control'
          ]) }}
          </div>
        </div>
        <!-- Purpose -->
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('purpose','Purpose') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('purpose',$purpose,Input::old('purpose'),[
            'id' => 'purpose',
            'class'=>'form-control'
          ]) }}
          <div class="checkbox">
            <label>
              <input type="checkbox" name="contains" id="contains"> Not in the list?
            </label>
          </div>
          {{ Form::textarea('description',Input::old('description'),[
            'id' => 'description',
            'class'=>'form-control',
            'placeholder'=>'Enter  details here...',
            'style' => 'display:none;'
          ]) }}
          </div>
        </div>
        <!-- creator name -->
        <div class="form-group">
          <div class="col-sm-3">
          {{ Form::label('faculty','Faculty-in-charge') }}
          </div>
          <div class="col-sm-9">
          {{ Form::select('faculty',$faculty,Input::old('faculty'),[
            'id'=>'faculty',
            'class'=>'form-control'
          ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-3">
            {{ Form::label('section','Course, Year & Section') }}
          </div>
          <div class="col-sm-9">
            {{ Form::text('section',Input::old('section'),[
              'class'=>'form-control',
              'placeholder'=>'Course Year-Section'
            ]) }}
          </div>
        </div>
      <div class="form-group">
        <div class="col-sm-12">
        <p class="text-muted text-justified">
          By clicking the request button, you agree to CCIS - LOO Terms and Conditions regarding reservation and lending equipments. <span class="text-danger"> The information filled up will no longer be editable and is final.</span>
        </p>
        </div>
      </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::submit('Create',[
              'class'=>'btn btn-lg btn-primary btn-block',
              'name' => 'create'
            ]) }}
          </div>
        </div>
      {{ Form::close() }}
      </div>
    </div> <!-- centered  -->
  </div><!-- Row -->
</div><!-- Container -->
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/bootstrap-clockpicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
<script>
  $(document).ready(function(){
    @if( Session::has("success-message") )
        swal("Success!","{{ Session::pull('success-message') }}","success");
    @endif
    @if( Session::has("error-message") )
        swal("Oops...","{{ Session::pull('error-message') }}","error");
    @endif

    $("#dateofuse").datepicker({
      language: 'en',
      showOtherYears: false,
      todayButton: true,
      minDate: new Date(),
      autoClose: true,
      onSelect: function(){
        $('#dateofuse').val(moment($('#dateofuse').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
      }
    });

    $("#dateofuse").val(moment({{ isset($date) ? "'".$date."'" : "" }}).format('MMM DD, YYYY'));

    $('#starttime').clockpicker({
        placement: 'bottom',
        align: 'left',
        // autoclose: true,
        default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
              $('#starttime').val(moment().format("hh:mmA"))
            },
            afterDone: function() {
              error('#time-start-error-message','*Time started must be less than time end')
            },
    });

    $('#endtime').clockpicker({
        placement: 'bottom',
        align: 'left',
        // autoclose: true,
        fromnow: 5400000,
        default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
              $('#endtime').val(moment().add("5400000").format("hh:mmA"))
            },
            afterDone: function() {
              error('#time-end-error-message','*Time ended must be greater than time started')
            },
    });

    function error(attr2,message){
      if($('#endtime').val()){
        if(moment($('#starttime').val(),'hh:mmA').isBefore(moment($('#endtime').val(),'hh:mmA'))){
          $('#request').show(400);
          $('#time-end-error-message').html(``)
          $('#time-start-error-message').html(``)
          $('#time-end-group').removeClass('has-error');
          $('#time-start-group').removeClass('has-error');
        }else{
          $('#request').hide(400);
          $(attr2).html(message).show(400)
          $('#time-end-group').addClass('has-error');
          $('#time-start-group').addClass('has-error');
        }
      }
    }
    
    $('#page-body').show();
  });
</script>
@stop
