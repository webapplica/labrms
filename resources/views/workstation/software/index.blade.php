@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body table-responsive">
            <legend>
                <h3 class="text-muted">{{ _('Workstation') }}: {{ $workstation->name }}</h3>
            </legend>

            <ul class="breadcrumb">       
                <li><a href="{{ url('workstation') }}">Workstation</a></li>
                <li>
					<a href="{{ url('workstation/' . $workstation->id) }}">
						{{ $workstation->name }}</a>
					</li>
                <li class="active">Software</li>
			</ul>
			
			<table 
				class="table table-hover table-striped table-bordered" 
				data-base-url="{{ url('workstation/' . $workstation->id . '/software') }}"
				data-install-url="{{ url('workstation/' . $workstation->id . '/software/install') }}"
				id="software-table">
				<thead>
					<th>Name</th>
					<th>License Key</th>
					<th>Date Installed</th>
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
		var table = $('#software-table');
		var baseUrl = table.data('base-url');
		var install = table.data('install-url');

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
	        ajax: baseUrl,
	        columns: [
	            { data: "name" },
	            { data: "license_key" },
	            { data: "pivot.created_at" },
	            { data: function(callback) {
	            	return `
						<a 
							href="` + baseUrl + `/`+callback.id + `" 
							class="btn btn-default btn-sm btn-block btn-stop-select">
							<span class="glyphicon glyphicon-eye-open"></span> View
						</a>
					`
	            } }
	        ],
	    } );	 	
		
		$("div.toolbar").html(`
			<a 
				id="new" class="btn btn-sm btn-primary"
				href="` + install + `">
				Install
			</a>
		`);
  	});
</script>
@stop
