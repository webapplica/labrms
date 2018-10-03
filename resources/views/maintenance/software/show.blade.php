@extends('layouts.master-blue')

@section('style')
<link href="{{ url('css/select.bootstrap.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
@include('modal.software.license.add')
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-body" style="margin-bottom: 10px;">
				<legend><h3 class="text-muted">Software License Information</h3></legend>
				<ol class="breadcrumb">
					<li><a href="{{ url('software') }}">Software</a></li>
					<li class="active">{{ $software->name }}</li>
					<li>License Keys</li>
				</ol>
				<table class="table table-hover table-striped table-bordered" id='softwareTable'>
					<thead>
						<th>ID</th>
						<th>License Key</th>
						<th>Usage Times</th>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
@stop

@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function(){
	    var table = $('#softwareTable').DataTable( {
	  		select: {
	  			style: 'single'
	  		},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-5'<'toolbar'>><'col-sm-4'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('software/license') }}" + '/' + {{ $software->id }},
	        columns: [
	            { data: "id" },
	            { data: "key" },
	            { data: "usage" },
	        ],
	    } );

	    $('div.toolbar').html(`
	    	<button class="btn btn-sm btn-primary" id="add" data-toggle="modal" data-target="#addSoftwareLicenseModal"><span class="glyphicon glyphicon-plus"></span> Add</button>
 			<button id="delete" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash"></span> Remove</button>
    	`)

	    table
	        .on( 'select', function ( e, dt, type, indexes ) {
	            // var rowData = table.rows( indexes ).data().toArray();
	            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
	            $('#edit').show()
	            $('#delete').show()
	        } )
	        .on( 'deselect', function ( e, dt, type, indexes ) {
	            // var rowData = table.rows( indexes ).data().toArray();
	            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
	            $('#edit').hide()
	            $('#delete').hide()
	        } );

	    $('#delete').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
			        swal({
			          title: "Are you sure?",
			          text: "This license will be removed. Do you want to continue",
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
							url: '{{ url("software/license") }}' + "/" + table.row('.selected').data().id,
							data: {
								'id': table.row('.selected').data().id
							},
							dataType: 'json',
							success: function(response){
								if(response == 'success'){
									swal('Operation Successful','License removed','success')
					        		table.row('.selected').remove().draw( false );
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
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    });
	})
</script>
@stop
