@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-12 panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Tickets</h3>
	</legend>

	@include('errors.alert')

	<table 
		class="table table-hover table-bordered table-striped table-condensed" 
		id="ticket-table"
		data-base-url="{{ url('ticket') }}"
		cellspacing=0
		width="100%">
		<thead>
			<th>ID</th>
			<th>Title</th>
			<th>Type</th>
			<th>Author</th>
			<th>Date Created</th>
			<th>Status</th>
			<th class="no-sort"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#ticket-table');
		var base_url = table.data('base-url');

	  	var dataTable = table.DataTable({
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
	     	 ajax: base_url,
			columns: [
				{ data: "id" },
				{ data: "title" },
				{ data: "type_name" },
				{ data: "author" },
				{ data: "human_readable_date" },
				{ data: "status"},
				{ data: function(callback){
					return `
						<div class="pull-left">
							<a href='{{ url("ticket") }}/` +  callback.id + `'' class='btn btn-md btn-default'>View More
							</a>
						</div>
					`;
				} }
			],
	  	});

	 	$("div.toolbar").html(`
			<a id="add" href = '{{ url('ticket/create') }}' class="btn btn-primary">
				Create
			</a>
		`);

		$('.ticket-filter').on('change',function (event) {
			url = setUrl()
			table.ajax.url(url).load();
		})

		function setUrl () {
			type = ""
			status = ""
			type = '&type=' + $('#ticket-type').val()
			status = '?status=' + $('#ticket-status').val()
			url = "{{ url('ticket') }}" +  status +  type
			history.pushState(null, '', url);
			return url
		}
    });
</script>
@stop
