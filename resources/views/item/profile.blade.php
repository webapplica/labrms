@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="" style="background-color: white;padding: 20px;">
		<legend><h3 class="text-muted">Items Profile</h3></legend>
		<table class="table table-hover table-striped table-condensed table-bordered table-responsive" id="roomTable">
			<thead>
				<th>ID</th>
				<th>Property Number</th>
				<th>Serial Number</th>
				<th>Location</th>
				<th>Brand</th>
				<th>Model</th>
				<th>Item Type</th>
				<th>Date Received</th>
				<th>Status</th>
				<th class="no-sort"></th>
			</thead>
		</table>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {
		init(1);

		function init(data)
		{

			table = $('#roomTable').DataTable({
					"processing": true,
					serverSide: true,
			        ajax: "{{ url('item/profile') }}",
			    	columnDefs:[
						{ targets: 'no-sort', orderable: false },
			    	],
				    language: {
				        searchPlaceholder: "Search..."
				    },
			    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
								    "<'row'<'col-sm-12'tr>>" +
								    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			        columns: [
			            { data: "id" },
			            { data: "property_number" },
			            { data: "serial_number" },
			            { data: "location_name" },
			            { data: "inventory.brand" },
			            { data: "inventory.model" },
			            { data: "inventory.itemtype.name" },
			            { data: 'parsed_date_received' },
			            { data: "status" },
				        { data: function(callback){
				        	return "<a href='{{ url("item/profile/history") }}" + '/' +  callback.id + "' class='btn btn-sm btn-default btn-block'>View History</a>"
				        } }
			        ],
			    } );

			 	$("div.toolbar").html(`
				  	Item type:
					  <select class="item-filter form-control" id="item-types-list">
							<option value='All'>
								All
							</option>
					      @foreach($item_types as $type)
							<option value='{{ $type->name }}' @if($current_type == $type->name) selected @endif>
								{{ $type->name }}
							</option>
					    @endforeach
					  </select>
				  	Status:
					  <select class="item-filter form-control" id="item-status-list">
					  	@foreach($item_statuses as $status)
						<option value='{{ $status }}' @if($current_status == $status) selected @endif>
							{{ $status }}
						</option>
						@endforeach
					  </select>
				`);
		}

		$('.item-filter').on('change',function(){
			setFilter()
		})

		function setFilter()
		{

			name = $('#item-types-list').val()
			status = $('#item-status-list').val()
			url = "{{ url('item/profile') }}" + '?type=' + name + '&&status=' + status
			table.ajax.url( url ).load();
			history.pushState(null, '', url)
		}

		$('#page-body').show();
	} );
</script>
@stop
