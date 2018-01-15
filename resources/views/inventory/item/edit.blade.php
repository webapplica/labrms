@extends('layouts.master-blue')
@section('title')
Inventory | Update
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/style.css')) }}
{{ HTML::style(asset('css/jquery-ui.min.css')) }}
<style>
	#page-body{
		display:none;
	}

	#podate,#invoicedate{
		background-color:white;
	}

</style>
@stop
@section('script-include')
{{ HTML::script(asset('js/jquery-ui.js')) }}
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-md-offset-3 col-md-6'>
		<div class="panel panel-body" style="padding-top: 20px;padding-left: 40px;padding-right: 40px;">
	      @if (count($errors) > 0)
         	 <div class="alert alert-danger alert-dismissible" role="alert">
	          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	              <ul class="list-unstyled" style='margin-left: 10px;'>
	                  @foreach ($errors->all() as $error)
	                      <li class="text-capitalize">{{ $error }}</li>
	                  @endforeach
	              </ul>
	          </div>
	      @endif
	 		{{ Form::open(['method'=>'put','route'=>array('inventory.item.update',$inventory->id),'class'=>'form-horizontal','id'=>'inventoryForm']) }}
	 		<div id="inventory">
				<legend><h3 style="color:#337ab7;">Inventory</h3></legend>
				<ul class="breadcrumb">
					<li><a href="{{ url('inventory/item') }}">Item Inventory</a></li>
					<li class="active">Update</li>
				</ul>
				<!-- item name -->
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('brand','Brand') }}
					{{ Form::text('brand',Input::old('brand'),[
						'class' => 'form-control',
						'placeholder' => 'Brand',
						'id' => 'brand'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('model','Model') }}
					{{ Form::text('model',Input::old('model'),[
						'class' => 'form-control',
						'placeholder' => 'Model',
						'id' => 'model'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('itemtype','Type') }}
					{{ Form::select('itemtype',['Fetching all item types...'],Input::old('itemtype'),[
						'class' => 'form-control',
						'placeholder' => 'Item type',
						'id' => 'itemtype'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('details','Item Details') }}
					{{ Form::textarea('details',Input::old('details'),[
						'class' => 'form-control',
						'placeholder' => 'Item Details',
						'id' => 'details',
						'rows' => 3
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('unit','Unit') }}
					{{ Form::text('unit',Input::old('unit'),[
						'class' => 'form-control',
						'placeholder' => 'Unit',
						'id' => 'unit'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('warranty','Warranty Information') }}
					{{ Form::textarea('warranty',Input::old('warranty'),[
						'class' => 'form-control',
						'placeholder' => 'Warranty Information',
						'id' => 'warranty'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<button type="submit" value="create" name="action" id="submit" class="btn btn-primary btn-lg btn-flat btn-block"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Update </button>
					</div>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop