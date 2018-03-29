@extends('layouts.master-blue')
@section('title')
Update
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
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
        <legend><h3 class="text-muted">Room Update</h3></legend>
        <ol class="breadcrumb">
          <li>
            <a href="{{ url('room') }}">Room</a>
          </li>
          <li class="active">{{ $room->id }}</li>
          <li class="active">Update</li>
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
        {{ Form::open(array('method'=>'put','route'=>array('room.update',$room->id),'class' => 'form-horizontal','id'=>'roomForm')) }}
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('name','Room Name') }}
            {{ Form::text('name', isset($room->name) ? $room->name : old('name'),[
              'required',
              'class'=>'form-control',
              'placeholder'=>'Room Name'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('category','Room Category') }}
            {{ Form::select('category[]',['Empty list'=>'Empty list'], old('category'),[
              'id' => 'category',
              'class'=>'form-control',
              'multiple' => 'multiple'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::label('description','Description') }}
            {{ Form::textarea('description', isset($room->description) ? $room->description : old('description'),[
              'class'=>'form-control',
              'placeholder'=>'Room Description'
            ]) }}
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{ Form::submit('Update',[
              'class'=>'btn btn-lg btn-primary btn-block',
              'name' => 'create',
              'id' => 'create'
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
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
<script>
  $(document).ready(function(){

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      type: 'get',
      url: '{{ url("room/category") }}',
      dataType: 'json',
      success: function(response){
        option = "";

        for( ctr = 0 ; ctr < response.data.length ; ctr++ ){
            option += `<option val=` + response.data[ctr].id + `>` + response.data[ctr].name + `</option>`;
        }

        $('#category').html("");
        $('#category').append(option);
      },
      complete: function(){
        $('#category').selectpicker('refresh');
        $('#category').selectpicker('val',[ 
            @foreach( explode(",",$room->category) as $category )
              {!! "'" . $category . "',"  !!}
            @endforeach
         ])
        $('#category').selectpicker();
      }
    })

  });
</script>
@stop
