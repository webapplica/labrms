@extends('layouts.master-blue')
@section('content')
{{ Form::open(array('class' => 'form-horizontal','method'=>'post','route'=>'type.store','id'=>'itemTypeForm')) }}
 <div class="container-fluid" id="page-body">
   <div class="row">
     <div class="col-md-offset-3 col-md-6">
       <div class="panel panel-body">
         <legend><h3 class="text-muted">Item Types</h3></legend>
         <ol class="breadcrumb">
             <li>
                 <a href="{{ url('item/type') }}">Item Type</a>
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
         @include('item.type.form')
        <div class="form-group">
          <div class="col-md-12">
            <button class="btn btn-primary btn-block btn-lg" type="submit">
              <span class="glyphicon glyphicon-check"></span> <span>Submit</span>
            </button>
          </div>
        </div>
       </div> <!-- centered  -->
     </div>
   </div>
  </div><!-- Container -->
@stop
