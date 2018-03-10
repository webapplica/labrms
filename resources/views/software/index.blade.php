@extends('layouts.master-blue')

@section('style')
{{ HTML::style(asset('css/bootstrap-multiselect.css')) }}
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body,#edit,#delete,#assign{
		display: none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	@include('modal.software.create')
	@include('modal.software.edit')
	@include('modal.software.assign')
	<div class="col-md-12" id="room-info">
		<div class="panel panel-body table-responsive" style="padding: 20px">
			<legend><h3 class="text-muted">Software</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-striped table-bordered" id='softwareTable'>
				<thead>
					<th>ID</th>
					<th>Software name</th>
					<th>Company</th>
					<th>License type</th>
					<th>Software type</th>
					<th>Minimum System Requirements</th>
					<th>Recommended System Requirements</th>
					<th>Room/s Assigned</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/bootstrap-multiselect.js')) }}
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function(){
	    var table = $('#softwareTable').DataTable( {
	    	serverSide: true,
			"pageLength": 100,
	  		select: {
	  			style: 'single'
	  		},
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
	        ajax: "{{ url('software') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "company" },
	            { data: "license_type" },
	            { data: "type" },
	            { data: "minimum_requirements" },
	            { data: "recommended_requirements" },
	            { data: function(callback){
	            	htmllist = ``
	            	callback.rooms.forEach(function(element){
	            		htmllist += `<button type="button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Removing..." class="remove btn btn-primary btn-sm" data-id="`+ callback.id +`" data-room="`+ element.id +`" style="border:none;margin:3px;">` + element.name + ` <span class="glyphicon glyphicon-remove"></span></button>`
	            	})
	            	return htmllist;
	            } },
	            { data: function(callback){
	          		return "<a href='{{ url("software/license") }}" + '/' +  callback.id + "' class='view btn btn-sm btn-default btn-block'>View License</a>"
	          	} }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<button id="new" class="btn btn-primary" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-plus"></span>  Create</button>
 			<button id="edit" class="btn btn-default" style="margin-right:5px;padding: 6px 10px;"><span class="glyphicon glyphicon-pencil"></span>  Update</button>
 			<button id="delete" class="btn btn-danger" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon glyphicon-trash"></span> Remove</button>
 			<button id="assign" class="btn btn-warning" style="margin-right:5px;padding: 5px 10px;"><span class="glyphicon"></span> Assign to a room</button>
		`);
 
    table
        .on( 'select', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#edit').show()
            $('#delete').show()
            $('#assign').show()
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            // var rowData = table.rows( indexes ).data().toArray();
            // events.prepend( '<div><b>'+type+' <i>de</i>selection</b> - '+JSON.stringify( rowData )+'</div>' );
            $('#edit').hide()
            $('#delete').hide()
            $('#assign').hide()
        } );

		$('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = '{{ url('software') }}' + '/' + table.row('.selected').data().id + '/edit'
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});

		$('#new').on('click',function(){
			window.location.href = "{{ url('software/create') }}"
		})

		$('#assign').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					$('#assign-software').val(table.row('.selected').data().id)
					$('#assign-room').data('room',table.row('.selected').data().rooms)
					$('#assignSoftwareModal').modal('show');
					$('#assignSoftwareModal').on('hide.bs.modal',function(){
					table.ajax.reload()
					})
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
		});

	    $('#delete').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
			        swal({
			          title: "Are you sure?",
			          text: "This software will be removed. Do you want to continue?",
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
							url: '{{ url("software/") }}' + "/" + table.row('.selected').data().id,
							dataType: 'json',
							success: function(response){
								swal('Operation Successful','Software removed','success')
							},
							error: function(){
								swal('Operation Unsuccessful','Error occurred while deleting a record','error')
							},
				    		complete: function(response){
				    			table.ajax.reload()
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

	    $('#softwareTable').on('click','.view',function(event)
	    {
    		event.stopPropagation();
	    })

	    $('#softwareTable').on('click','.remove',function(event)
	    {
    		var button = $(this).button('loading')
	    	event.preventDefault()
    		event.stopPropagation();
	    	$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
	    		type: 'post',
	    		url: '{{ url("software/room/remove") }}' + '/' + $(this).data('id') + '/' + $(this).data('room'),
	    		dataType: 'json',
	    		success: function(response){
	    			swal('Operation Success','Software unlinked from room','success')
	    		},
	    		error: function(response){
	    			swal('Operation Failed','Problem occurred while processing data. Please reload the page','error')
	    		},
	    		complete: function(response){
	    			table.ajax.reload()
	    			button.button('reset');
	    		}
	    	})
	    })

	    $('#table tbody').on( 'click', 'tr', function () {
	      if ( $(this).hasClass('selected') ) {
	          $(this).removeClass('selected');
	      }
	      else {
	          table.$('tr.selected').removeClass('selected');
	          $(this).addClass('selected');
	      }
	    } );

		$(document).ajaxComplete(function(){		
	        $('#edit').hide()
	        $('#delete').hide()
	        $('#assign').hide()
		});

		$('#page-body').show();
	});
</script>
@stop
