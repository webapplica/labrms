@extends('layouts.master-blue')

@section('style')
<link rel="stylesheet" href="{{ url('css/style.css') }}"  />
<style>

	.toolbar {
    	float:left;
	}

	textarea{
		resize:none;
		overflow-y:hidden;
	}
</style>
@stop
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
					<th>Allowed?</th>
					<th>Status</th>
					<th class="no-sort"></th>
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
    	"dom": "<'row'<'col-sm-6'<'toolbar'>><'col-sm-3 text-center'><'col-sm-3'f>>" +
					    "<'row'<'col-sm-12'tr>>" +
					    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
      ajax: "{{ url('reservation/items/list') }}",
      columns: [
          { data: "id" },
          { data: "local_id" },
          { data: "property_number" },
          { data: "inventory.brand" },
          { data: "inventory.model" },
          { data: "inventory.itemtype.name" },
          { data: "reservation_status" },
          { data: "status" },
          { data: function(){
          	return `
          		<button type="button" class="allow btn btn-default"> Allow | Disallow </button>
          	`
          } },
    	],
  	});


  	$('#reservationRulesTable').on('click', '.allow', function(){
  		id = $(this).data('id')

  		$.ajax({
  			
  		})
  	})
  });
</script>
@stop
