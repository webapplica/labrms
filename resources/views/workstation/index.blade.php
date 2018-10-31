@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body table-responsive">
			<legend>
				<h3 class="text-muted">Workstations</h3>
			</legend>

			<table 
				class="table table-hover table-striped table-bordered" 
				id="workstation-table"
				data-base-url="{{ url('workstation') }}">
				<thead>
					<th>ID</th>
					<th>OS</th>
					<th>Name</th>
					<th>System Unit</th>
					<th>Monitor</th>
					<th>AVR</th>
					<th>Keyboard</th>
					<th>Mouse</th>
					<th>Location</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function() {

		var table = $('#workstation-table');
		var base_url = table.data('base-url');

    	var dataTable = table.DataTable( {
	  		select: {
	  			style: 'single'
	  		},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: base_url,
	        columns: [
	        	{ data: 'id' },
	            { data: "oskey" },
	            { data: "name" },
	            { data: "system_unit_local" },
	            { data: "monitor_local" },
	            { data: "avr_local" },
	            { data: "keyboard_local" },
	            { data: "mouse_local" },
	            { data: 'location' },
	            { data: function(callback) {
	            	return `
						<a href="` + base_url + `/`+callback.id+`" class="btn btn-default btn-sm btn-block btn-stop-select"><span class="glyphicon glyphicon-eye-open"></span> 	View</a>
						<button id="update" class="btn btn-success" style="margin-right:5px;padding: 5px 10px;">
							<span class="glyphicon glyphicon-wrench"></span>  Update Parts
						</button>
						<button id="deploy" class="btn btn-default" style="margin-right:5px;padding: 5px 10px;">
							<span class="glyphicon glyphicon-share-alt"></span>  Deploy
						</button>
						<button id="transfer" class="btn btn-warning" style="margin-right:5px;padding: 5px 10px;">
							<span class="glyphicon glyphicon-share"></span>  Transfer
						</button>
						<button id="delete" class="btn btn-danger" data-loading-text="Loading..." style="margin-right:5px;padding: 5px 10px;">
							<span class="glyphicon glyphicon-trash"></span> Condemn
						</button>
					`
	            } }
	        ],
	    } );

	    $('#workstationTable').on('click', '.btn-stop-select', function(e) {
	    	e.stopPropagation();
	    })

	 	$("div.toolbar").html(`
			<a id="new" class="btn btn-primary" style="margin-right:5px;padding: 5px 10px;" href="{{ url('workstation/create') }}">
				<span class="glyphicon glyphicon-plus"></span>  Assemble
			</a>
		`);
  	});
</script>
@stop
