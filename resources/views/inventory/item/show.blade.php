@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Inventory: {{ $inventory->brand }} - {{ $inventory->model }}</h3>
	</legend>

	<ol class="breadcrumb">
		<li><a href="{{ url('inventory') }}">Inventory</a></li>
		<li class="active">{{ $inventory->brand }}</li>
		<li class="active">{{ $inventory->model }}</li>
	</ol>

	<table 
		class="table table-hover table-striped table-bordered table-condensed" 
		id="inventory-table" 
		cellspacing="0" 
		width="100%"
		data-base-url="{{ url('inventory/' . $inventory->id) }}"
		data-profile-items-url="{{ url('inventory/' . $inventory->id . '/profile') }}"
		data-item-url="{{ url('item') }}"
		>
		<thead>
			<tr rowspan="2">
				<th class="text-left" colspan="6"> ID:  
					<span style="font-weight:normal">{{ $inventory->code }}</span> 
				</th>
				<th class="text-left" colspan="6"> Total Quantity:  
					<span style="font-weight:normal">{{ $inventory->quantity }}</span> 
				</th>
			</tr>
			<tr rowspan="2">
				<th class="text-left" colspan="6"> Brand:  
					<span style="font-weight:normal">{{ $inventory->brand }}</span> 
				</th>
				<th class="text-left" colspan="6"> Model:  
					<span style="font-weight:normal">{{ $inventory->model }}</span> 
				</th>
			</tr>
			<tr rowspan="2">
				<th class="text-left" colspan="6"> Type:  
					<span style="font-weight:normal">{{ $inventory->item_type_name }}</span>  
				</th>
				<th class="text-left" colspan="6"> Unit:  
					<span style="font-weight:normal">{{ $inventory->unit_name }}</span>  
				</th>
			</tr>
			<tr>
				<th>ID</th>
				<th>Local</th>
				<th>Property Number</th>
				<th>Serial Number</th>
				<th>Location</th>
				<th>Date Received</th>
				<th>Date Profiled</th>
				<th>Reservation Status</th>
				<th>Status</th>
				<th class="no-sort"></th>
			</tr>
		</thead>
	</table>
</div>
@stop
@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#inventory-table');
		var base_url = table.data('base-url');
		var profile_items_url = table.data('profile-items-url');
		var item_url = table.data('item-url');

		var dataTable = table.DataTable({
			serverSide: true,
			processing: true,
			select: {
				style: 'single'
			},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			language: {
					searchPlaceholder: "Search..."
			},
	    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			ajax: base_url,
			columns: [
				{ data: "id" },
				{ data: "local_id" },
				{ data: "property_number" },
				{ data: "serial_number" },
				{ data: "location" },
				{ data: "parsed_date_received" },
				{ data: "parsed_date_profiled"},
				{ data: "reservation_status" },
				{ data: "status" },
				{ data: function(callback){
					return `

						<a href="` + item_url + `/` + callback.id + `" class="btn btn-sm btn-default btn-block">
							<span class="glyphicon glyphicon-list" aria-hidden="true"></span> View
						</a>
					`
				} }
			],
		});

		$('div.toolbar').html(`
			<a
				href="` + profile_items_url + `"
				class="btn btn-primary">
				Profile Items
			</a>
		`);
	} );
</script>
@stop
