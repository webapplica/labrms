@extends('layouts.app')

@section('body-content')
<div class="container-fluid" id="login" style="margin-top: 50px;">
  <div class="row">
    <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6">
      <div class="panel panel-body">
        <div class="col-sm-12" id="loginPanel" style="padding: 20px 20px 0px 20px" >
          <legend class=hidden-xs>
            <div class="row center-block" style="margin-bottom: 10px;">
              <div class="col-xs-4" style="padding-right:5px;">
                <img class=" img img-responsive pull-right" src="{{ asset('images/logo/ccis/ccis-logo-64.png') }}" style="width: 64px;height: auto;"/>
              </div>
              <div class="col-xs-8" style="padding-left:5px;">
                <h4 class="text-muted pull-left">{{ config('company.department') }}</h4>
              </div>
            </div>
          </legend>
          <div id="form-body" class="form-container" style="margin-top: 10px;">
            {{ Form::open(['class' => 'form-horizontal', 'id'=>'loginForm']) }}
            <div class="form-group">
              <div class="col-md-12">
                {{ Form::label('username','Username') }}
                {{ Form::text('username',Input::old('username'), [
                  'required',
                  'id'=>'username',
                  'class'=>'form-control',
                  'placeholder'=>'Username',
                  'id' => 'username'
                ]) }}
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
              {{ Form::label('Password') }}
              {{ Form::password('password', [
                  'required',
                  'id'=>'password',
                  'class'=>'form-control',
                  'placeholder'=>'Password',
              ]) }}
              </div>
            </div>
            <div class="form-group text-center center-block">
              <div class="col-md-12">
                  <button 
                    type="submit" 
                    id="loginButton" 
                    data-loading-text="Logging in..." 
                    class="btn btn-md btn-primary btn-block" 
                    autocomplete="off">
                    Login
                </button>
              </div>
            </div>
            <hr />
            <a 
              href="{{ url('reset') }}" 
              class="text-center text-muted center-block" 
              type="button" 
              role="button" 
              style="text-decoration: none;" >

              <small style="letter-spacing: 2px;">Forgot your password?</small>
            </a>
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </div> <!-- centered  -->
  </div><!-- Row -->
</div><!-- Container -->
@stop
@section('script')
<script type="text/javascript" src="{{ asset('js/loadingoverlay/loadingoverlay.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/loadingoverlay/loadingoverlay_progress.min.js') }}"></script>
<script>
  $(document).ready(function(){

    $("#loginForm").submit(function(e){
        e.preventDefault();
        var $btn = $('#loginButton').button('loading')
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'post',
          url:'{{ url("login") }}',
          data:{
            'username':$('#username').val(),
            'password':$('#password').val()
          },
          success:function(response){
            if(response.toString() == 'success') {
              alert('successs', 'Succeed with authentication. You will now be redirected to Dashboard');
              
              (setInterval(function(){
                window.location.href = '{{ url('login') }}';
              }, 2000));
            } else {
              alert('danger', 'Credentials submitted does not exists');
            }
          },
          error:function(response){
              alert('danger', 'Problem occurred while sending your data to the servers');
          },
          complete: function(response) {
            $btn.button('reset')
            $('#password').val('')
          },
        });
    })

    function alert(type, message) {
      $('.form-container').prepend(`
          <div class="alert alert-` + type + ` alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul class="list-unstyled" id="list-error">
              <li><span class="glyphicon glyphicon-remove"></span>` + message + `</li>
            </ul>
          </div>
      `)
    }

    $(document).ajaxStart(function(){
      $.LoadingOverlay("show");
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });
  });
</script>
@stop
