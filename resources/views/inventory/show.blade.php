@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body" style="padding: 20px;">
		<legend>
			<h3 class="text-muted">Inventory: Logs</h3>
		</legend>

		<ul class="breadcrumb">
			<li class='active'><a href="{{ url('inventory') }}">Inventory</a></li>
			<li>{{ $inventory->code }}</li>
			<li>Logs</li>
		</ul>

		<table 
			class="table table-hover table-striped table-condensed table-bordered table-responsive" 
			id="inventory-table"
			data-base-url="{{ url("inventory/$inventory->id/log") }}"
			data-release-url="{{ url("inventory/$inventory->id/release") }}"
			data-receive-url="{{ url("inventory/$inventory->id/receive") }}"
			>
			<thead>
	          <tr rowspan="2">
	              <th class="text-left" colspan="4">Code:  
	                <span style="font-weight:normal">{{ $inventory->code }}</span> 
	              </th>
	              <th class="text-left" colspan="4">Type:  
	                <span style="font-weight:normal">{{ $inventory->item_type_name }}</span> 
	              </th>	
	          </tr>
	          <tr rowspan="2">
	              <th class="text-left" colspan="4">Brand:  
	                <span style="font-weight:normal">{{ $inventory->brand }}</span> 
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
					<th>ID</th>
					<th>Accountable</th>
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

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#inventory-table');
		var base_url = table.data('base-url');
		var release_url = table.data('release-url');
		var receive_url = table.data('receive-url');

		var dataTable = table.DataTable({
			processing: true,
			serverSide: true,
	        ajax: base_url,
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
				<a href="` + release_url + `" role="button" id="release" class="btn btn-danger btn-sm">Release</a>
				<a href="` + receive_url + `" role="button" id="release" class="btn btn-success btn-sm">Receive</a>
		`)
	} );
</script>
@stop
