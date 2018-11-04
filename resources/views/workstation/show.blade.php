@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="panel panel-default" style="padding:0px 20px">
		<div class="panel-body">
			<legend>
				<h3 class="text-muted">{{ $workstation->name }}</h3>
			</legend>

			<ul class="breadcrumb">
				<li><a href="{{ url('workstation') }}">Workstation</a></li>
				<li class="active">{{ $workstation->name }}</li>
				<li class="active">Information</li>
			</ul>

			<table 
				class="table table-bordered" 
				id="history-table"
				data-base-url="{{ url('workstation/' . $workstation->id) }}" 
				style="width: 100%;">
				<thead>
					<tr rowspan=2>
						<td colspan=3>
							{{ _('Workstation Name') }}:
							<span style="font-weight: normal;">{{ $workstation->name }}</span>
						</td>
						<td colspan=3>
							{{ _('License Key') }}
							<span style="font-weight: normal;">{{ $workstation->oskey }}</span>
						</td>
					</tr>

					<tr rowspan=2>
						<td colspan=3>
							{{ _('System Unit') }}:
							<span style="font-weight: normal;">{{ $workstation->system_unit_local }}</span>
						</td>
						<td colspan=3>
							{{ _('Monitor') }}
							<span style="font-weight: normal;">{{ $workstation->monitor_local }}</span>
						</td>
					</tr>

					<tr rowspan=2>
						<td colspan=3>
							{{ _('AVR') }}:
							<span style="font-weight: normal;">{{ $workstation->avr_local }}</span>
						</td>
						<td colspan=3>
							{{ _('Keyboard') }}
							<span style="font-weight: normal;">{{ $workstation->keyboard_local }}</span>
						</td>
					</tr>

					<tr rowspan=2>
						<td colspan=3>
							{{ _('Mouse') }}:
							<span style="font-weight: normal;">{{ $workstation->mouse_local }}</span>
						</td>
						<td colspan=3>
							{{ _('Location') }}
							<span style="font-weight: normal;">{{ $workstation->location }}</span>
						</td>
					</tr>

					<tr rowspan=2>
						<td colspan=3>
							{{ _('Tickets Issued') }}:
							<span style="font-weight: normal;"></span>
						</td>
						<td colspan=3>
							{{ _('Mouse Issued') }}
							<span style="font-weight: normal;"></span>
						</td>
					</tr>

					<tr>
						<th class="col-md-1">ID</th>
						<th class="col-md-2">Name</th>
						<th class="col-md-2">Details</th>
						<th class="col-md-2">Author</th>
						<th class="col-md-2">Status</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
$(document).ready(function() {

	var table = $('#history-table');
	baseUrl = table.data('base-url');

	var dataTable = table.DataTable( {
		serverSide: true,
		"processing": true,
	    language: {
	        searchPlaceholder: "Search..."
	    },
	    order: [[ 0, "desc" ]],
		"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						"<'row'<'col-sm-12'tr>>" +
						"<'row'<'col-sm-5'i><'col-sm-7'p>>",
        ajax: baseUrl,
        columns: [
        	{ data: 'id' },
        	{ data: 'title' },
        	{ data: 'details' },
        	{ data: 'author' },
        	{ data: 'status' }
        ],
    } );

	$('div.toolbar').html(`
		<button 
			id="update" 
			class="btn btn-sm btn-success" 
			style="margin-right:5px; padding: 5px 10px;">
			<span class="glyphicon glyphicon-wrench"></span>  Update Parts
		</button>
		<button 
			id="deploy" 
			class="btn btn-sm btn-default" 
			style="margin-right:5px; padding: 5px 10px;">
			<span class="glyphicon glyphicon-share-alt"></span>  Deploy
		</button>
		<button 
			id="transfer" 
			class="btn btn-sm btn-warning" 
			style="margin-right:5px; padding: 5px 10px;">
			<span class="glyphicon glyphicon-share"></span>  Transfer
		</button>
		<button 
			id="delete" 
			class="btn btn-sm btn-danger" 
			data-loading-text="Loading..." 
			style="margin-right:5px; padding: 5px 10px;">
			<span class="glyphicon glyphicon-trash"></span> Condemn
		</button>
	`);
})
</script>
@stop
