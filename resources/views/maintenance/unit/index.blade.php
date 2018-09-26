@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>Unit</legend>

	<table class="table table-striped table-hover table-bordered" id='unit-table'>
		<thead>
			<th class="col-sm-1">ID</th>
			<th class="col-sm-1">Name</th>
			<th class="col-sm-1">Abbreviation</th>
			<th class="col-sm-1">Description</th>
			<th class="no-sort col-sm-1"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#unit-table');
	    var dataTable = table.DataTable( {
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('unit') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "abbreviation" },
	            { data: "description" },
	            { data: function(callback){
	            	return `
	            			<a href="{{ url("unit") }}` + '/' + callback.id + '/edit' + `" class="btn btn-sm btn-default">Edit</a>
	            			<button 
	            				type="button" 
	            				data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Removing Unit" 
	            				data-id="`+callback.id+`" 
	            				class="remove btn btn-sm btn-danger">
	            				Remove
            				</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="{{ url('unit/create') }}" id="new" class="btn btn-primary">
 				Add
 			</a>
		`);

		table.on('click','button.remove',function(){	
		  	var removeButton = $(this);
			removeButton.button('loading');

			$.ajax({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
				type: 'delete',
				url: '{{ url("unit") }}' + '/' + $(this).data('id'),
				dataType: 'json',
				success: function (response) {
					if(response == 'success')
						swal("Operation Success",'Unit removed.',"success")
					else
						swal("Error Occurred",'An error has occurred while processing your data.',"error")
				},
				error: function (response) {
					swal("Error Occurred",'An error has occurred while processing your data.',"error")
				},
				complete: function (response) {
					table.ajax.reload()
			  		removeButton.button('reset');
				}

			})
		})
	})
</script>	
@stop
