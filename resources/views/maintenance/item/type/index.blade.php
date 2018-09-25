@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body  table-responsive">

	<legend><h3 class="text-muted">Item Types</h3></legend>

	<table class="table table-striped table-bordered" id='item-type-table'>

		<thead>
			<th>ID</th>
			<th>Type</th>
			<th>Description</th>
			<th>Category</th>
			<th></th>
		</thead>
	</table>

</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {

    	var table = $('#item-type-table').DataTable( {
			"pageLength": 100,
	  		select: {
	  			style: 'single'
	  		},
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
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
						<form method="post" action="{{ url('item/type') }}/` + callback.id + `">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<input type="hidden" name="_method" value="DELETE" />
							<a href="{{ url('item/type') }}/`+ callback.id +`/edit" class="edit btn btn-sm btn-default">
								Update
							</a>
							<button data-id="`+ callback.id +`" class="btn-delete btn btn-sm btn-danger">
								Remove
							</button>
						</form
					`;
				} }
	        ],
    	} );

	 	$("div.toolbar").html(`
 			<a href="{{ url('item/type/create') }}" id="new" class="btn btn-primary">
 				Add New Type
			</a>
		`);

	});
</script>
@stop
