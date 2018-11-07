@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="col-md-12">
		<div class="panel panel-body table-responsive">
			<legend>
                <h3 class="text-muted">Reservations</h3>
            </legend>
            <table 
                class="table table-hover table-condensed table-bordered table-striped" 
				id="reservation-table"
				data-ajax-url="{{ url('reservation') }}"
				data-base-url="{{ url('reservation') }}">
				<thead>
					<th>ID</th>
					<th>Reserved By</th>
					<th>Faculty in-charge</th>
					<th>Date and Time</th>
					<th>Location</th>
					<th>Status</th>
					<th class="no-sort col-sm-1"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function() {

        var table = $('#reservation-table');
		var baseUrl = table.data('base-url');
		var ajaxUrl = table.data('ajax-url');

		var dataTable = table.DataTable( {
			serverSide: true,
			processing: true,
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	  		"order": [[ '4','desc' ]],
			"processing": true,
	        ajax: ajaxUrl,
	        columns: [
	            { data: "id" },
	            { data: 'reservee' },
	            { data: "accountable" },
	            { data: 'parsed_date_and_time'},
				{ data: "location" },
				{ data: "status_name" },
				{ data: function (callback) {
					
					return `
						<a
							href="` + baseUrl + '/' + callback.id + `"
							class="btn btn-sm btn-default"
							>View More</a>
					`;	

				} }
	        ],
	    } );
	} );
</script>
@stop
