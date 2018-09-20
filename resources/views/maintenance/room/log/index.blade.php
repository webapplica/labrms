@extends('layouts.master-blue')

@section('styles-include')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
@stop

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Laboratory Rooms</h3>
	</legend>

	<table class="table table-hover table-condensed table-bordered table-striped" id="roomTable">
		<thead>
			<th>ID</th>
			<th>Name</th>
			<th>Category</th>
			<th>Description</th>
			<th class="no-sort col-sm-1"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#roomTable').DataTable( {
	  		select: {
	  			style: 'single'
	  		},
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-9'l><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('room') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "category" },
	            { data: "description" },
	            { data: function(callback){
	            	return `
	            		<a href="` + '{{ url('room/log') }}' + '/' + callback.id +`" class="btn btn-default btn-sm btn-block">View Log</a>
	            	`;
	            } }
	        ],
	    } );
	} );
</script>
@stop
