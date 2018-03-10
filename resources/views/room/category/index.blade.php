@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-sm-offset-2 col-sm-8" id="room-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Laboratory Room Categories</h3></legend>
	        
			@include('errors.alert')

			<table class="table table-hover table-condensed table-bordered table-striped" id="roomTable">
				<thead>
					<th>ID</th>
					<th>Category</th>
					<th class="col-sm-2 no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#roomTable').DataTable( {
			serverSide: true,
			processing: true,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-8'l><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'<'toolbar'>>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('room/category') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: function(callback){
	            	return `
	            		<button type="button" data-name="`+ callback.name + `" data-id="`+callback.id+`" class="btn btn-sm btn-default edit">Edit</button>
	            		<button type="button" data-name="`+ callback.name + `" data-id="`+callback.id+`" class="btn btn-sm btn-danger delete">Delete</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
	 		{{ Form::open(['method'=>'post','route'=>'room.category.store','id'=>'categoryCreationForm']) }}
	 		{{ Form::text('name',Input::old('name'),[
	 			'class' => 'form-control',
	 			'id' => 'category-name',
	 			'style' => 'display:none',
	 			'placeholder' => 'Category Name'
	 		]) }}
 			<button type="button" id="new" class="btn btn-success" style="margin-right:5px;" ><span class="glyphicon glyphicon-plus"></span> Add</button>
 			<button type="button" id="hide" class="btn btn-default" style="margin-right:5px;display:none;"><span class="glyphicon glyphicon-eye-close"></span> Hide</button>
 			{{ Form::close() }}
		`);

		$('#new').on('click',function(){
			if($('#category-name').is(':hidden'))
			{		
				$('#category-name').toggle(400)
				$('#hide').toggle(400)
			}
			else
			{
				$('#categoryCreationForm').submit()
			}
		})

		$('#hide').on('click',function(){
			$('#category-name').toggle(400)
			$('#hide').toggle(400)
		})

		$('#roomTable').on('click','.edit',function(){
	    	name = $(this).data('name')
	    	id = $(this).data('id')
	    	swal({
			  title: "Input Category!",
			  text: "Input the new category you want to update it to",
			  type: "input",
			  showCancelButton: true,
			  closeOnConfirm: false,
			  animation: "slide-from-top",
			  inputValue: name
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
			  	type: 'put',
			  	url: '{{ url("room/category") }}' + '/' + id,
			  	dataType: 'json',
			  	data: {
			  		'name': inputValue
			  	},
			  	success: function(response){
		  			swal('Success','Information Updated','success')	
			  		table.ajax.reload();
			  	},
			  	error: function(){
			  		swal('Error','Problem Occurred while processing your data','error')
			  	}
			  })
			});
		});

	    $('#roomTable').on('click','.delete',function(){
	    	id = $(this).data('id')
	    	name = $(this).data('name')
	        swal({
	          title: "Are you sure?",
	          text: "This room will be removed from database?",
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
					url: '{{ url("room/category") }}' + "/" + id,
					data: {
						'id': id,
						'name': name
					},
					dataType: 'json',
					success: function(response){
						swal('Operation Successful','Room Category removed','success')
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
	} );
</script>
@stop
