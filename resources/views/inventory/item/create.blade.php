@extends('layouts.master-blue')
@section('title')
Inventory | Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-md-offset-3 col-md-6'>
		<div class="panel panel-body" style="padding-top: 20px;padding-left: 40px;padding-right: 40px;">
	 		{{ Form::open(['method'=>'post','route'=>'inventory.store','class'=>'form-horizontal','id'=>'inventoryForm']) }}
			<legend><h3 style="color:#337ab7;"><span id="form-name">Inventory</span></h3></legend>
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory') }}">Item Inventory</a></li>
				<li class="active">Create</li>
			</ul>
			@include('errors.alert')
			@if(isset($receipts) && count($receipts) > 0)
			<div class="form-group">
				<div class="col-sm-12">
					{{ Form::label('receipt', 'Receipt Number') }}
					{{ Form::select('receipt', (count($receipts) > 0) ? $receipts : [], isset($inventory->receipt_id) ? $inventory->receipt_id : null,  [
							'class' => 'form-control'
					]) }}
				</div>
			</div>
			@endif
			@include('inventory.item.form')
			<div class="form-group">
				<div class="col-sm-12">
					<button type="submit" value="create" name="action" id="submit" class="btn btn-lg btn-primary btn-flat btn-block"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit </button>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function(){

		$('#brand #itemtype #model').on('change',function(){
			url = "{{ url('get') }}" + '/' + $('#itemtype').val() + '/' + $('#brand').val() + '/' + $('#model').val()
			setValue(url)
		});

		setValue(function(){
			url = "{{ url('get') }}" + '/' + $('#itemtype').val() + '/' + $('#brand').val() + '/' + $('#model').val()
			return url
		})

		function setValue(_url)
		{
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'get',
                url: _url,
				dataType: 'json',
				success: function(response){
					if(response != 'error')
					{
						$('#alert-existing').html("<div class='alert alert-success'><strong>Item exists!</strong> The quantity you inputted will be appended to the existing item</div>")
						$('#details').val(response.details)
						$('#unit').val(response.unit)
						$('#warranty').val(response.warranty)
						// $('#details').prop("readonly","readonly")
						// $('#unit').prop("readonly","readonly")
						// $('#warranty').prop("readonly","readonly")
					} else {
						$('#alert-existing').html("<div class='alert alert-warning'><strong>Warning!</strong> This will create a new inventory item</div>")
						// $('#details').removeProp("readonly")
						// $('#unit').removeProp("readonly")
						// $('#warranty').removeProp("readonly")
					}
				}
			})
		}

		$('#brand').autocomplete({
			source: "{{ url('get/inventory/item/brand') }}"
		})

		$('#model').autocomplete({
			source: "{{ url('get/inventory/item/model') }}"
		})

		$('#link-to-inventory').click(function(){
			$('#form-name').text('Inventory')
			$('#page-two').hide(600);
			$('#receipt').hide(600);
			$('#inventory').show();
			$('#page-one').show(600);
		});

		$('#link-to-receipt').click(function(){
			$('#form-name').text('Receipt')
			$('#inventory').hide(600);
			$('#receipt').show(600);
		});
	})
</script>
@stop
