@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Inventory</h3>
	</legend>

	<table 
		class="table table-hover table-striped table-bordered table-condensed" 
		id="inventory-table"
		data-base-url="{{ url('inventory') }}"
		data-view-profiled-url="{{ url('item/profile') }}">
		<thead>
			<th class="col-md-1">ID</th>
			<th class="col-md-1">Model</th>
			<th class="col-md-1">Brand</th>
			<th class="col-md-1">Type</th>
			<th class="col-md-1">Unit</th>
			<th class="col-md-1">Quantity</th>
			<th class="col-md-1">Unprofiled</th>
			<th class="col-md-3 no-sort"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#inventory-table');
		var base_url = table.data('base-url');
		var view_profiled_url = table.data('view-profiled-url');

	    var dataTable = table.DataTable({
			serverSide: true,
			processing: true,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f><'search-bar'>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			ajax: base_url,
			columns: [
					{ data: "code" },
					{ data: "model" },
					{ data: "brand" },
					{ data: "item_type_name" },					
					{ data: "unit_name" },
					{ data: "quantity" },
					{ data: "unprofiled" },
					{ data: function (callback) {

						log_url = base_url + '/' + callback.id + '/log';
						view_profiled = view_profiled_url + '/' + callback.id;

						return `
							<a href="` + log_url + `" class="btn btn-primary btn-sm" type="button">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								<span class="hidden-sm hidden-xs">Logs</span>
							</a>
							<a href="` + view_profiled + `" id="view" class="btn btn-sm btn-default" type="button">
								<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
								<span class="hidden-sm hidden-xs">View Profiled</span>
							</a>
						`
					}}
			],
	    });

	 	$("div.toolbar").html(`
			<button id="add-inventory" class="btn btn-md btn-primary">
				<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
				<span id="nav-text">Add</span>
			</button>
		`);

	 	$('div.search-bar').html(`
	 		<div class="col-sm-12">
				<a href="{{ url('inventory/search') }}" class="pull-right btn-link" style="font-size: 10px;">
					Advance Search
				</a>
			</div>
 		`)

	    $('#add-inventory').on('click', function () {
				window.location.href = "{{ url('inventory/create') }}";
	    })
	} );
</script>
@stop
