@extends('layouts.master-blue')
@section('title')
Item Sub Type
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-sm-offset-2 col-sm-8" id="room-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Item Sub Types</h3></legend>
	        @if (count($errors) > 0)
	            <div class="alert alert-danger alert-dismissible" role="alert">
	            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <ul style='margin-left: 10px;'>
	                    @foreach ($errors->all() as $error)
	                        <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>
	        @endif
			<table class="table table-hover table-condensed table-bordered table-striped" id="itemSubType">
				<thead>
					<th>ID</th>
					<th>Name</th>
					<th>Type</th>
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
		var table = $('#itemSubType').DataTable( {
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
	        ajax: "{{ url('item/subtype') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "itemtype.name" },
	            { data: function(callback){
	            	return `
	            		<button type="button" data-id="`+callback.id+`" data-name="`+callback.name+`" class="btn btn-sm btn-default edit">Edit</button>
	            		<button type="button" data-id="`+callback.id+`" data-name="`+callback.name+`" class="btn btn-sm btn-danger delete">Delete</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
	 		{{ Form::open(['method'=>'post','route'=>'subtype.store','id'=>'nameCreationForm']) }}
	 		{{ Form::text('name',Input::old('name'),[
	 			'class' => 'form-control',
	 			'id' => 'subtype-name',
	 			'style' => 'display:none',
	 			'placeholder' => 'Item Sub Type'
	 		]) }}
	 		{{ Form::select('itemtype',$itemtypes,Input::old('itemtype'),[
	 			'class' => 'form-control',
	 			'id' => 'itemtype',
	 			'style' => 'display:none'
	 		]) }}
 			<button type="button" id="new" class="btn btn-success" style="margin-right:5px;" ><span class="glyphicon glyphicon-plus"></span> Add</button>
 			<button type="button" id="hide" class="btn btn-default" style="margin-right:5px;display:none;"><span class="glyphicon glyphicon-eye-close"></span> Hide</button>
 			{{ Form::close() }}
		`);

		$('#new').on('click',function(){
			if($('#subtype-name').is(':hidden'))
			{		
				$('#subtype-name').toggle(400)
				$('#itemtype').toggle(400)
				$('#hide').toggle(400)
			}
			else
			{
				$('#nameCreationForm').submit()
			}
		})

		$('#hide').on('click',function(){
			$('#subtype-name').toggle(400)
			$('#itemtype').toggle(400)
			$('#hide').toggle(400)
		})

		$('#itemSubType').on('click','.edit',function(){
	    	name = $(this).data('name')
	    	id = $(this).data('id')
	    	window.location.href = "{{ url('item/subtype') }}" + "/" + id + "/edit"
	  //   	swal({
			//   title: "Input Sub Type!",
			//   text: "Input the new sub type you want to update it to",
			//   type: "input",
			//   showCancelButton: true,
			//   closeOnConfirm: false,
			//   animation: "slide-from-top",
			//   inputValue: name
			// },
			// function(inputValue){
			//   if (inputValue === false) return false;
			  
			//   if (inputValue === "") {
			//     swal.showInputError("You need to write something!");
			//     return false
			//   }
			  
			//   $.ajax({
			//     headers: {
			//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			//     },
			//   	type: 'put',
			//   	url: '{{ url("item/subtype") }}' + '/' + id,
			//   	dataType: 'json',
			//   	data: {
			//   		'name': inputValue
			//   	},
			//   	success: function(response){
			//   		if(response == 'success')
			//   		{
			//   			swal('Success','Information Updated','success')	
			//   		}
			//   		else
			//   		swal('Error','Problem Occurred while processing your data','error')
			//   		table.ajax.reload();
			//   	},
			//   	error: function(){
			//   		swal('Error','Problem Occurred while processing your data','error')
			//   	}
			//   })
			// });
		});

	    $('#itemSubType').on('click','.delete',function(){
	    	name = $(this).data('name')
	    	id = $(this).data('id')
	        swal({
	          title: "Are you sure?",
	          text: "This item subtype will be removed from database?",
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
					url: '{{ url("item/subtype") }}' + "/" + id,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Item Sub Type removed','success')
			        		table.ajax.reload();
			        	}else{
							swal('Operation Unsuccessful','Error occurred while deleting a record','error')
						}
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

		@if( Session::has("success-message") )
			swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
			swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$('#page-body').show();
	} );
</script>
@stop
