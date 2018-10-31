@extends('layouts.app')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="panel panel-default" style="padding:0px 20px">
		<div class="panel-body">
			<div class="col-sm-12" style="margin-bottom: 20px;">
				<legend>
					<h3 class="text-muted">{{ $workstation->name }}</h3>
				</legend>
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
			</div>

			<div class="col-sm-12">	
				<ul class="breadcrumb">
					<li><a href="{{ url('workstation') }}">Workstation</a></li>
					<li class="active">{{ $workstation->name }}</li>
					<li class="active">Information</li>
				</ul>
			</div>

			<div class="col-sm-12">
				  <!-- Default panel contents -->
				  <h3 class="line-either-side text-info">Basic Information</h3>
			</div>

			<!-- List group -->
			<ul class="col-sm-12 list-unstyled display-information">
				<li class="text-muted">
					<span>
						<i class="fa fa-newspaper-o" aria-hidden="true"></i> Name: 
					</span>
					<span>
						{{ $workstation->name }}
					</span>
				</li>

				<li class="text-muted">
					<span>
						<i class="fa fa-key" aria-hidden="true"></i> License Key: 
					</span>
					<span>{{ $workstation->oskey }}</span>
				</li>

				<li class="text-muted">
					<span>
						<i class="fa fa-server" aria-hidden="true"></i> System Unit: 
					</span>
					<span>
					</span>
				</li>

				<li class="text-muted" >
					<span>
						<i class="fa fa-desktop" aria-hidden="true"></i> Monitor:
					</span>
					<span>
					</span>
				</li>

				<li class="text-muted" >
					<span>
						<i class="fa fa-power-off" aria-hidden="true"></i> AVR: 
					</span>
				</li>
				
				<li class="text-muted" >
					<span>
						<i class="fa fa-keyboard-o" aria-hidden="true"></i> Keyboard: 
					</span>
					<span>
					</span>
				</li>

				<li class="text-muted" >
					<span>
						<i class="fa fa-mouse-pointer" aria-hidden="true"></i> Mouse: 
					</span>
					<span>
					</span>
				</li>

				<li class="text-muted" >
					<span>
						<i class="fa fa-location-arrow" aria-hidden="true"></i> Location: 
					</span>
				</li>

				<li class="text-muted" >
					<span>
						Tickets: 
					</span>
				</li>

				<li class="text-muted" >
					<span>
						Mouse Issued: 
					</span>
				</li>
			</ul>
	                                            
			<div class="col-sm-12">

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
			    <li role="presentation" class="active"><a href="#software" aria-controls="software" role="tab" data-toggle="tab">Software</a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane" id="history">
			    	<div class="panel panel-body" style="padding: 10px;">
						<table class="table table-bordered" id="historyTable" style="width:100%;">
							<thead>
					            <th>ID</th>
					            <th>Name</th>
					            <th>Details</th>
					            <th>Author</th>
					            <th>Status</th>
					        </thead>
						</table>
					</div>
				</div>
				
			    <div role="tabpanel" class="tab-pane active" id="software">
			    	<div class="panel panel-body" style="padding: 10px;">
						<table class="table table-bordered" id="softwareTable">
							<thead>
								<th>Software</th>
								<th>Status</th>
							</thead>
						</table>
					</div>
				</div>
				
			  </div>
			</div>
		</div>
	</div>
</div>
@stop

@section('script')
<script type="text/javascript">
$(document).ready(function(){

	var historyTable = $('#historyTable').DataTable( {
		serverSide: true,
		"processing": true,
	    language: {
	        searchPlaceholder: "Search..."
	    },
	    order: [[ 0, "desc" ]],
        ajax: "{{ url("workstation/$workstation->id") }}",
        columns: [
        	{ data: 'id' },
        	{ data: 'title' },
        	{ data: 'details' },
        	{ data: 'author' },
        	{ data: 'status' }
        ],
    } );

	var table = $('#softwareTable').DataTable( {
		serverSide: true,
		"processing": true,
    	columnDefs:[
			{ targets: 'no-sort', orderable: false },
    	],
	    language: {
	        searchPlaceholder: "Search..."
	    },
        ajax: "{{ url("workstation/$workstation->id/softwares") }}",
        columns: [
        	{ data: "name"},
        	{ data: function(callback){

        		key = callback.license_key

        		if(!key) key = "No License Key";

        		edit = `<button class="btn btn-default btn-sm pull-right" data-pc='{{ $workstation->id }}' data-software='`+ callback.id +`' data-target='#updateSoftwareWorkstationModal' data-toggle='modal'>Change License</button>`
        		remove = `<button class="remove btn btn-danger btn-sm pull-right" data-pc='{{ $workstation->id }}' data-software="`+ callback.id +`" data-loading-text="Uninstalling...." autocomplete="off">Uninstall</button>`

        		if(key != 'No License Key' || callback.workstation != null)
    				return `Installed:  ` + " " + key + edit + remove
    			else
    				return "<i>Not Installed</i>  <button class='install btn btn-success btn-sm pull-right' data-pc='{{ $workstation->id }}' data-software='"+ callback.id +"' data-target='#installSoftwareWorkstationModal' data-toggle='modal'>Install</button>"
        	}}
        ],
    } );

    $('#softwareTable').on('click', '.remove', function() {
    	pc = $(this).data('pc')
    	software = $(this).data('software')
		var $btn = $(this).button('loading')

    	$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
    		type: 'delete',
    		url: '{{ url("workstation/software/$workstation->id/remove") }}',
    		data: {
    			'software': software
    		},
    		dataType: 'json',
    		success: function(response){
				swal('Operation Success','','success')
    			table.ajax.reload()
    			historyTable.ajax.reload()
    		},
    		error: function(response){
				swal('Error occurred while processing your request','','error')
    		},
    		complete: function(response){
				$btn.button('reset')
    		}
    	})

    })

})
</script>
@stop
