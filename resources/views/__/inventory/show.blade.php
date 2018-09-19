@extends('layouts.master-blue')

@section('content')
@include('modal.inventory.release')
<div class="container-fluid" id="page-body">
	<div class="" style="background-color: white;padding: 20px;">
		<legend><h3 class="text-muted">Inventory Logs</h3></legend>
		<ul class="breadcrumb">
			<li class='active'><a href="{{ url('inventory') }}">Inventory</a></li>
			<li>{{ $inventory->code }}</li>
			<li>Logs</li>
		</ul>
		<table class="table table-hover table-striped table-condensed table-bordered table-responsive" id="inventoryTable">
			<thead>
	          <tr rowspan="2">
	              <th class="text-left" colspan="4">Code:  
	                <span style="font-weight:normal">{{ $inventory->code }}</span> 
	              </th>
	              <th class="text-left" colspan="4">Type:  
	                <span style="font-weight:normal">{{ $inventory->itemtype->name }}</span> 
	              </th>	
	          </tr>
	          <tr rowspan="2">
	              <th class="text-left" colspan="4">Brand:  
	                <span style="font-weight:normal">{{ $inventory->model }}</span> 
	              </th>
	              <th class="text-left" colspan="4">Model:  
	                <span style="font-weight:normal">{{ $inventory->model }}</span> 
	              </th>
	          </tr>
	          <tr rowspan="2">
	              <th class="text-left" colspan="4">Details:  
	                <span style="font-weight:normal">{{ $inventory->details }}</span> 
	              </th>
	              <th class="text-left" colspan="4">Unit:  
	                <span style="font-weight:normal">{{ $inventory->unit_name }}</span> 
	              </th>
	          </tr>
	          <tr rowspan="2">
					<th>Log Number</th>
					<th>User</th>
					<th>Details</th>
					<th>Quantity Released</th>
					<th>Quantity Received</th>
					<th>Remaining Balance</th>
					<th>Date</th>
	          </tr>
			</thead>
		</table>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		$('#inventoryTable').DataTable({
			"processing": true,
			serverSide: true,
	        ajax: "{{ url("inventory/$inventory->id/log") }}",
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	        columns: [
	            { data: "id" },
	            { data: "user_info" },
	            { data: "details" },
	            { data: "quantity_issued" },
	            { data: "quantity_received" },
	            { data: "remaining_balance" },
	            { data: 'parsed_date' },
	        ],
	    } );

	    $('.toolbar').html(`
	    		<button type="button" id="release" class="btn btn-danger btn-sm" data-id="{{ $inventory->id }}" data-target="#releaseInventoryModal" data-toggle="modal">Release</button>
	    	`)

		$('#releaseInventoryModal').on('show.bs.modal', function (e) {
		  	$('#inventory-id').val('{{ $inventory->id }}')
		})

	    @if( Session::has('show-modal') )
	    	$('#releaseInventoryModal').modal('show')
	    @endif
	} );
</script>
@stop
