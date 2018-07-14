@extends('layouts.master-blue')

@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
{{ HTML::style(asset('css/font-awesome.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body,#edit,#delete{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="room-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Reservations</h3></legend>
			<table class="table table-hover table-condensed table-bordered table-striped" id="reservationTable">
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
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#reservationTable').DataTable( {
		    language: {
		        searchPlaceholder: "Search..."
		    },
			serverSide: true,
			processing: true,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	  		"order": [[ '4','desc' ]],
			"processing": true,
	        ajax: "{{ url('reservation') }}",
	        columns: [
	            { data: "id" },
	            { data: 'reservee_name' },
	            { data: "accountable" },
	            { data: 'parsed_date_and_time'},
				{ data: "purpose" },
				{ data: "location" },
				{ data: "status_name" },
				{ data: 'remarks' },
				{ data: function(callback){
					if( callback.is_cancelled || callback.is_claimed || callback.is_disapproved ) {
						return '<p class="text-muted">No Action</p>'
					} else if( callback.is_approved )
					{
						return `
							<button data-id="`+callback.id+`" data-reason="`+callback.remark+`" class="disapprove btn btn-xs btn-danger"><i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i></button>
						`
					} else {
						return `
							<button data-id="`+callback.id+`" class="approve btn btn-xs btn-success"><i class="fa fa-thumbs-o-up fa-2x" aria-hidden="true"></i></button>
							<button data-id="`+callback.id+`" data-reason="`+callback.remark+`"  class="disapprove btn btn-xs btn-danger"><i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i></button>
						`
					}

				} }
	        ],
	    } );

	    $('#reservationTable').on('click','.approve',function(){
	    	id = $(this).data('id')
			swal({
			  title: "Are you sure?",
			  text: "Do you really want to approve this reservation?",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			  confirmButtonText: "Yes, approve it!",
			  cancelButtonText: "No, cancel it!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function(isConfirm){
			  if (isConfirm) {
				$.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
					type: 'post',
					url: '{{ url("reservation") }}' + "/" + id + '/approve',
					dataType: 'json',
					success: function(response) {
						swal('Operation Successful','Operation Complete','success')

					}, 
					error: function(response) {
						swal('Operation Unsuccessful','Error occurred while processing your request','error')
					}, 
					complete: function() {
						table.ajax.reload();
					}
	       		})
			  } else {
			    swal("Cancelled", "Request Cancelled", "error");
			  }
			});
	    });

	    $('#reservationTable').on('click','.disapprove',function(){
	    	id = $(this).data('id')
	        swal({
				  title: "Remarks!",
				  text: "Input reason for disapproving the reservation",
				  type: "input",
				  showCancelButton: true,
				  closeOnConfirm: false,
				  animation: "slide-from-top",
				  inputPlaceholder: "Write something"
	        },
	        function(inputValue){
				if (inputValue === false) return false;

				if (inputValue === "") {
					swal.showInputError("You need to write something!");
					return false
				}

				$.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
					type: 'post',
					url: '{{ url("reservation") }}' + "/" + id + '/disapprove',
					data: {
						'reason': inputValue
					},
					dataType: 'json',
					success: function(response) {
						swal('Operation Successful','Operation Complete','success')
					},
					error: function(response){
						swal('Operation Unsuccessful',response.errors,'error')
					},
					complete: function() {
						table.ajax.reload();
					}
	       		})
	       	})
	    });
	} );
</script>
@stop
