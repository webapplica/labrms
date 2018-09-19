@extends('layouts.app')

@section('body-content')
  <div class="col-md-offset-3 col-md-6" style="padding: 25px auto; margin-top: 50px; margin-bottom: 50px;">
    <div class="col-sm-12 col-md-2 center-block" style="align-items: center; display: flex;">
      <img class=" img img-responsive" src="{{ asset('images/logo/ccis/ccis-logo-64.png') }}" style="width: auto; height: auto; max-height: 120px;"/>
    </div>
    <div class="col-sm-12 col-md-9 hidden-xs">
      <h4 class="text-muted" style="color: white;">{{ config('company.header') }}</h4>
      <h5 class="text-muted" style="color: white;">{{ config('company.department') }}</h5>
      <h6 class="text-muted" style="color: white;">{{ config('company.address') }}</h6>
    </div>
  </div>

  <div class="clearfix"></div>

  <div class="col-md-offset-3 col-md-6 panel panel-body" style="padding: 30px;">

    <form class="form-horizontal" id="loginForm" method="post" action="{{ url('login') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="form-group col-md-12">
          {{ Form::label('username','Username') }}
          {{ Form::text('username',Input::old('username'), [
            'required',
            'id'=>'username',
            'class'=>'form-control',
            'placeholder'=>'Username',
            'id' => 'username'
          ]) }}
        </div>

        <div class="form-group col-md-12">
          {{ Form::label('Password') }}
          {{ Form::password('password', [
              'required',
              'id'=>'password',
              'class'=>'form-control',
              'placeholder'=>'Password',
          ]) }}
        </div>

        <div class="form-group text-center center-block col-md-12">
          <button 
            type="submit" 
            id="loginButton" 
            data-loading-text="Logging in..." 
            class="btn btn-md btn-primary btn-block" 
            autocomplete="off">
            Login
          </button>
        </div>

        <a 
          href="{{ url('reset') }}" 
          class="text-center text-muted center-block" 
          type="button" 
          role="button" 
          style="text-decoration: none; padding: 20px 0;" >

          <small style="letter-spacing: 2px;">Forgot your password?</small>
        </a>
    </form>
  </div>
@stop
