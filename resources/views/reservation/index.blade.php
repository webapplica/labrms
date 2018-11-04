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
				data-ajax-url="{{ url('reservation') }}">
				<thead>
					<th>ID</th>
					<th>Reserved By</th>
					<th>Faculty in-charge</th>
					<th>Date and Time</th>
					<th>Purpose</th>
					<th>Location</th>
					<th>Status</th>
					<th>Remarks</th>
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
	        ajax: table.data('ajax-url'),
	        columns: [
	            { data: "id" },
	            { data: 'reservee_name' },
	            { data: "accountable" },
	            { data: 'parsed_date_and_time'},
				{ data: "purpose" },
				{ data: "room_name" },
				{ data: "status_name" },
				{ data: 'remarks' },
				{ data: function(callback){
					if( callback.is_cancelled || callback.is_claimed || callback.is_disapproved ) {
						return '<p class="text-muted">No Action</p>'
					} else if( callback.is_approved )
					{
						return `
							<button 
                                data-id="` + callback.id + `" 
                                data-reason="` + callback.remark + `" 
                                class="disapprove btn btn-xs btn-danger">
                                <i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i>
                            </button>
						`
					} else {
						return `
							<button 
                                data-id="`+callback.id+`" 
                                class="approve btn btn-xs btn-success">
                                <i class="fa fa-thumbs-o-up fa-2x" aria-hidden="true"></i>
                            </button>
							<button 
                                data-id="`+callback.id+`" 
                                data-reason="`+callback.remark+`"  
                                class="disapprove btn btn-xs btn-danger">
                                <i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i>
                            </button>
						`
					}

				} }
	        ],
	    } );
	} );
</script>
@stop
