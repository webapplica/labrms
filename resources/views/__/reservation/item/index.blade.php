@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body table-responsive" style="padding: 25px 30px;">
			<legend><h3 class="text-muted">Items for Reservation</h3></legend>
			<table class="table table-hover table-bordered" id="reservationRulesTable">
				<thead>
					<th>ID</th>
					<th>Local ID</th>
					<th>Property Number</th>
					<th>Brand</th>
					<th>Model</th>
					<th>Type</th>
					<th>Status</th>
					<th class="no-sort">Enabled</th>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function() {

  	var table = $('#reservationRulesTable').DataTable({
  		processing: true,
  		serverSide: true,
  		select: {
  			style: 'single'
  		},
	    language: {
	        searchPlaceholder: "Search..."
	    },
		"processing": true,
      	ajax: "{{ url('reservation/items/list') }}",
		columns: [
			{ data: "id" },
			{ data: "local_id" },
			{ data: "property_number" },
			{ data: "inventory.brand" },
			{ data: "inventory.model" },
			{ data: "inventory.itemtype.name" },
			{ data: "status" },
			{ data: function(callback){
				html = `<label class="switch">
				<input class="is-enabled" type="checkbox"`
				if(callback.reservation_status == 'Yes') {
					html += ` checked`;
				}
				
				html += 
				` data-id="`+callback.id+`"><span class="slider round"></span>
				</label>
				`
				return html;
			} },
		],
  	});


  	$('#reservationRulesTable').on('click', '.is-enabled', function(){
  		id = $(this).data('id')
		checked = $(this).is(':checked');

  		$.ajax({
			headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          	},
          	type:'post',
			url:'{{ url("reservation/items/list") }}',
			data:{
				'id': id,
				'checked': checked,
			},
			success:function(response) {
				alert_success('Status successfully updated');
			},
			error:function(response) {
				alert_error('Error encountered while updating your status');
			},
			complete: function(response) {
				table.ajax.reload();
			}
  		})
  	})

    $(document).ajaxStart(function(){
      $.LoadingOverlay("show");
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });
  });
</script>
@stop
