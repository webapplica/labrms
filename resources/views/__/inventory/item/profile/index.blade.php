@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
@include('modal.inventory.item.assign')
	<div class="col-md-12">
		<div class="panel panel-body table-responsive">

			<legend><h3 class="text-muted">{{ $inventory->itemtype->name }} Inventory</h3></legend>
			<ol class="breadcrumb">
			  <li><a href="{{ url('inventory') }}">Item Inventory</a></li>
			  <li class="active">{{{ $inventory->model }}}</li>
			</ol>

			<table class="table table-hover table-striped table-bordered table-condensed" id="itemProfileTable" cellspacing="0" width="100%">
				<thead>
		          <tr rowspan="2">
		              <th class="text-left" colspan="6">Brand:  
		                <span style="font-weight:normal">{{ $inventory->brand }}</span> 
		              </th>
		              <th class="text-left" colspan="6">Model:  
		                <span style="font-weight:normal">{{ $inventory->model }}</span> 
		              </th>
		          </tr>
		          <tr rowspan="2">
		              <th class="text-left" colspan="6">Item Type:  
		                <span style="font-weight:normal">{{ $inventory->itemtype->name }}</span>  
		              </th>
		              <th class="text-left" colspan="6"> 
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
						<th>Status</th>
						<th class="no-sort"></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function() {

		var table = $('#itemProfileTable').DataTable({
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
			"processing": true,
				ajax: "{{ url("item/profile/$inventory->id") }}",
				columns: [
						{ data: "id" },
						{ data: "local_id" },
						{ data: "property_number" },
						{ data: "serial_number" },
						{ data: "location_name" },
						{data: "parsed_date_received" },
						{data: "parsed_date_profiled"},
						{ data: "status" },
						{ data: function(callback){
							return `
							<a href="{{ url('item/profile/history') }}/`+callback.id+`" class="btn btn-sm btn-default">
								<span class="glyphicon glyphicon-list" aria-hidden="true"></span> View
							</a>
						  	<button class="btn btn-success btn-sm" data-toggle="modal" data-id="`+callback.id+`" data-property_number="`+callback.property_number+`" data-serial_number="`+callback.serial_number+`" data-location="`+callback.location_name+`" data-target="#assignModal" id="assign">
						  		<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Assign
						  	</button>
							<button class="btn btn-danger btn-sm delete" type="button" data-id="`+callback.id+`">
								<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
								<span class="hidden-sm hidden-xs">Condemn</span>
							</button>
							`
						} }
				],
		});

	    $('#itemProfileTable').on('click', '.delete', function(){
	    	id = $(this).data('id')
	 		swal({
	          title: "Are you sure?",
	          text: "This record will be considered as condemned and will be removed. Do you want to continue?",
	          type: "warning",
			  confirmButtonColor: "#DD6B55",
	          showCancelButton: true,
	          confirmButtonText: "Yes, delete it!",
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
					type: 'delete',
					url: '{{ url("item/profile/") }}' + "/" + id,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Item condemned','success')
			        	}else if(response == 'connected'){
							swal('Operation Unsuccessful','This item is used in a workstation. You cannot remove it here. You need to proceed to workstation','error')
			        	}else{
							swal('Operation Unsuccessful','Error occurred while deleting a record','error')
						}
					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while deleting a record','error')
					},
					complete: function() {
						table.ajax.reload();
					}
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
	    });

	} );
</script>
@stop
