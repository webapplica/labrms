@extends('layouts.master-blue')

@section('content')
@include('modal.receipt.create')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="receipt-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Receipts</h3></legend>
			<table class="table table-hover table-condensed table-bordered table-striped" id="receiptTable">
				<thead>
					<th class="">ID</th>
					<th class="">Number</th>
					<th class="">Purchase Order</th>
					<th class="">Invoice</th>
					<th class="">Fund Cluster</th>
					<th class="no-sort"></th>
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
	        ajax: "{{ url('receipt') }}",
	        columns: [
	            { data: "id" },
	            { data: "number" },
	            { data: "purchaseorder_number" },
	            { data: "invoice_number" },
	            { data: "fund_code" },
	            { data: function(callback){
	            	return `
	            		<a href="` + '{{ url('receipt') }}' + '/' + callback.id +`" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list"></span> View</a>
 						<a href="` + '{{ url('receipt') }}' + '/' + callback.id +`/edit" class="edit btn btn-sm btn-warning"><span class="glyphicon glyphicon-pencil"></span>  Edit</a>
 						<button class="delete btn btn-sm btn-danger" data-id="` + callback.id + `"><span class="glyphicon glyphicon-trash"></span> Remove</button>
	            	`;
	            } }
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
