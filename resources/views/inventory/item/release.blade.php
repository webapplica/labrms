@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-md-offset-3 col-md-6'>
		<div class="panel panel-body" style="padding: 20px 40px;">

	 		{{ Form::open(['method'=>'post', 'url'=> url('inventory/' . $inventory->id . '/release'), 'id'=>'item-inventory-form']) }}

			<legend><h3 style="color:#337ab7;"><span id="form-name">Inventory: {{ $inventory->summarized_name }}</span></h3></legend>
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory') }}">Inventory</a></li>
                <li>Item</li>
                <li>{{ $inventory->summarized_name }}</li>
				<li class="active">Release</li>
			</ul>

            @include('errors.alert')
            
            @include('inventory.item.partials.release_form')

			<div class="form-group">
				<button 
					type="submit" 
					value="create" 
					name="action" 
					id="submit" 
					class="btn btn-lg btn-primary btn-flat btn-block">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit 
				</button>
			</div>

			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
