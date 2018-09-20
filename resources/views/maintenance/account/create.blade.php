@extends('layouts.app')

@section('body-content')
<div class="container-fluid col-md-offset-3 col-md-6 panel panel-body">

  <legend>
    <h3>Add New Account</h3>
  </legend>

  <ol class="breadcrumb">
      <li><a href="{{ url('account') }}">Account</a></li>
      <li class="active">Create</li>
  </ol>

  @include('errors.alert')

  {{ Form::open([ 'id'=>'registration-form', 'route'=>'account.store' ]) }}

  @include('maintenance.account.partials.form')

  <div class="form-group">
      <button 
        class="btn btn-lg btn-primary btn-block"
        id="register"
        type="submit"
        value="Create">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Create
      </button>
  </div>

  {{ Form::close() }}

</div>
@stop

@section('scripts-include')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready( function() {

    $( "#registration-form" ).validate( {
      rules: {
        firstname: "required",
        lastname: "required",
        username: {
          required: true,
          minlength: 4
        },
        password: {
          required: true,
          minlength: 8
        },
        confirm: {
          required: true,
          minlength: 8,
          equalTo: "#password"
        },
        contactnumber: {
          required: true,
          minlength: 11,
          maxlength: 11
        },
        email: {
          required: true,
          email: true
        }
      },
      messages: {
        firstname: "Please enter your firstname",
        lastname: "Please enter your lastname",
        username: {
          required: "Please enter a username",
          minlength: "Your username must consist of at least 4 characters"
        },
        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 8 characters long"
        },
        confirm: {
          required: "Please provide a password",
          minlength: "Your password must be at least 8 characters long",
          equalTo: "Please enter the same password as above"
        },
        contactnumber: {
          required: "Please provide your contact number",
          minlength: "Contact Number must be 11-digit",
          minlength: "Contact Number must be 11-digit"
        },
        email: "Please enter a valid email address"
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        error.addClass( "help-block" );
        element.parents( ".form-group" ).addClass( "has-feedback" );

        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        } else {
          error.insertAfter( element );
        }

        if ( !element.next( "span" )[ 0 ] ) {
          $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
        }
      },
      success: function ( label, element ) {
        if ( !$( element ).next( "span" )[ 0 ] ) {
          $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
        }
      },
      submitHandler: function(form) {
        swal({
          title: "Are you sure?",
          text: "Account information will be added to the database.",
          type: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, submit it!",
          cancelButtonText: "No, cancel it!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm){
          if (isConfirm) {
            form.submit();
          } else {
            swal("Cancelled", "Operation Cancelled", "error");
          }
        })
      },
      highlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".form-group" ).addClass( "has-error" ).removeClass( "has-success" );
        $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
      },
      unhighlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".form-group" ).addClass( "has-success" ).removeClass( "has-error" );
        $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
      }
    } );
  });
</script>
@stop
