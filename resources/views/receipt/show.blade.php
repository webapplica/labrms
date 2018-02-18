@extends('layouts.master-blue')

@section('content')
@include('modal.receipt.create')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="receipt-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">{{ $receipt->number }}</h3></legend>
			<ul class="breadcrumb">
				<li>
					<a href="{{ url('receipt') }}">Receipt</a>
				</li>
				<li class="active">
					{{ $receipt->number }}
				</li>
			</ul>
			<table class="table table-hover table-condensed table-bordered table-striped" id="receiptTable">
				<thead>
					<tr>
						<th colspan=4>ID: {{ $receipt->id }}</th>
						<th colspan=4>Number: {{ $receipt->number }}</th>
					</tr>
					<tr>
						<th colspan=4>P.O. Number: {{ $receipt->purchaseorder_number }}</th>
						<th colspan=4>Invoice Number: {{ $receipt->invoice }}</th>
					</tr>
					<tr>
						<th class="">Brand</th>
						<th class="">Model</th>
						<th class="">Item Type</th>
						<th class="">Quantity</th>
						<th class="">Unit</th>
						<th class="">Unit Cost</th>
						<th>Profiled Count</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#receiptTable').DataTable( {
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url("receipt/$receipt->id") }}",
	        columns: [
	            { data: "brand" },
	            { data: "model" },
	            { data: "itemtype.name" },
	            { data: "unit_name" },
	            { data: "quantity" },
	            { data: "pivot.received_unitcost" },
	            { data: "unprofiled" },
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<button type="button" id="new" class="btn btn-primary btn-md" data-toggle="modal" data-target="#createReceiptModal"><span class="glyphicon glyphicon-plus"></span>  Add</button>
		`);

	    $('#receiptTable').on('click', '.delete', function(){
	    	id = $(this).data('id')
	        swal({
	          title: "Are you sure?",
	          text: "This receipt will be removed from database?",
	          type: "warning",
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
					url: '{{ url("receipt/") }}' + "/" + id,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Receipt removed from database','success')
			        	}else{
							swal('Operation Unsuccessful','Error occurred while deleting a record','error')
						}

						table.ajax.reload()
					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while deleting a record','error')
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
