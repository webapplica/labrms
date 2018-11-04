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
			</ul>

			<table 
				class="table table-bordered" 
				id="history-table"
				data-base-url="{{ url('workstation/' . $workstation->id) }}" 
				data-update-parts-url="{{ url("workstation/$workstation->id/edit") }}"
				data-deploy-url="{{ url("workstation/$workstation->id/deploy") }}"
				data-transfer-url="{{ url("workstation/$workstation->id/transfer") }}"
				data-disassemble-url="{{ url("workstation/$workstation->id/disassemble") }}"
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
	var baseUrl = table.data('base-url');
	var update_parts_url = table.data('update-parts-url');
	var deploy_url = table.data('deploy-url');
	var transfer_url = table.data('transfer-url');
	var disassemble_url = table.data('disassemble-url');

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

	$('div.toolbar').append(

		$('<a>', {
			class: 'btn btn-sm btn-success',
			text: 'Update Parts',
			style: 'margin-right: 5px;',
			href: update_parts_url
		}).prepend( $('<span>', { class: 'glyphicon glyphicon-wrench', style: 'margin-right: 5px;'}) ),

		$('<a>', {
			class: 'btn btn-sm btn-default',
			text: 'Deploy',
			style: 'margin-right: 5px;',
			href: deploy_url
		}).prepend( $('<span>', { class: 'glyphicon glyphicon-share-alt', style: 'margin-right: 5px;'}) ),

		$('<a>', {
			class: 'btn btn-sm btn-warning',
			text: 'Transfer',
			style: 'margin-right: 5px;',
			href: transfer_url
		}).prepend( $('<span>', { class: 'glyphicon glyphicon-share', style: 'margin-right: 5px;'}) ),
		
		$('<a>', {
			class: 'btn btn-sm btn-danger',
			text: 'Disassemble',
			style: 'margin-right: 5px;',
			href: disassemble_url
		}).prepend( $('<span>', { class: 'glyphicon glyphicon-trash', style: 'margin-right: 5px;'}) ),
	);
	
})
</script>
@stop
