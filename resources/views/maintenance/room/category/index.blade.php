@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-12 panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Laboratory Room Categories</h3>
	</legend>

	<table 
		class="table table-hover table-striped table-bordered" 
		id="room-table"
		data-csrf-token="{{ csrf_token() }}"
		data-base-url="{{ url('room/category') }}">
		<thead>
			<th class=>ID</th>
			<th class=>Name</th>
			<th class="no-sort"></th>
		</thead>
	</table>
</div>
@endsection

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#room-table');
		var csrf_token = table.data('csrf-token');
		var base_url = table.data('base-url');


		var table = $('#room-table').DataTable({
			serverSide: true,
			processing: true,
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
	        ajax: "{{ url('room/category') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: function (callback) {
	            	return `
						<form method="post" action="` + base_url + '/' + callback.id + `">

							<input type="hidden" name="_token" value="` + csrf_token + `" />
							<input type="hidden" name="_method" value="delete" />

							<a href="` + base_url + '/' + callback.id + `/edit" class="btn btn-warning">
								Update
							</a>

							<button type="submit" data-id="` + callback.id + `" class="remove-btn btn btn-danger">
								Remove
							</button>

						</form>
	            	`;
	            } }
	        ],
	    } );

	    $('div.toolbar').html(`
			<a href="{{ url('room/category/create') }}" class="btn btn-primary">Create</a>
    	`)
	} );
</script>
@stop
