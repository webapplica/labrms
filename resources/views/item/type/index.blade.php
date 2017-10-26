@extends('layouts.master-blue')
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="itemtype-info">
		<div class="col-sm-12 panel panel-body  table-responsive">
			<legend><h3 class="text-muted">Item Types</h3></legend>
			<table class="table table-striped table-hover table-bordered" id='itemTypeTable'>
				<thead>
					<th>ID</th>
					<th>Item type</th>
					<th>Description</th>
					<th>Category</th>
					<th></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@include('modal.item.type.create')
@include('modal.item.type.edit')
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function(){

    var table = $('#itemTypeTable').DataTable( {
		"pageLength": 100,
  		select: {
  			style: 'single'
  		},
	    language: {
	        searchPlaceholder: "Search..."
	    },
    	"dom": "<'row'<'col-sm-9'<'toolbar'>><'col-sm-3'f>>" +
					    "<'row'<'col-sm-12'tr>>" +
					    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		"processing": true,
        ajax: "{{ url('item/type') }}",
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "description" },
            { data: "category" },
            { data: function(callback){
							return `
								<button data-id="`+ callback.id +`" class="edit btn btn-sm btn-default btn-flat" style="margin-right:5px;padding: 6px 10px;">
									<span class="glyphicon glyphicon-pencil"></span>  Update
								</button>
								<button data-id="`+ callback.id +`" class="delete btn btn-sm btn-danger btn-flat" style="margin-right:5px;padding: 5px 10px;">
									<span class="glyphicon glyphicon-trash"></span> Remove
								</button>
							`;
						} }
        ],
    } );

	 	$("div.toolbar").html(`
 			<button id="new" class="btn btn-primary btn-flat" style="margin-right:5px;padding: 5px 10px;" data-target="createItemTypeModal" data-toggle="modal"><span class="glyphicon glyphicon-plus"></span>  Add</button>
		`);

    $('#new').on('click',function(){
    	$('#createItemTypeModal').modal('show');
    	// window.location.href = "{{ url('item/type/create') }}"
    })

		$('#itemTypeTable').on('click','.edit',function(){
			id = $(this).data('id')
			window.location.href = 'type/' + id + '/edit';
		});

		$('#itemTypeTable').on('click','.delete',function(){
			id = $(this).data('id')
      swal({
        title: "Are you sure?",
        text: "This Item Type will be removed from database?",
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
						url: '{{ url("item/type/") }}' + "/" + id,
						data: {
							'id': id
						},
						dataType: 'json',
						success: function(response){
							if(response == 'success'){
								swal('Operation Successful','Item Type removed from database','success')
				        	}else{
								swal('Operation Unsuccessful','Error occurred while deleting a record','error')
							}
							
							table.ajax.reload();
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

	});
</script>
@stop
