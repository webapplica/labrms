@extends('layouts.master-blue')

@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
{{ HTML::style(asset('css/font-awesome.min.css')) }}
@endsection

@section('content')
<div class="container-fluid" id="page-body">

	@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)

	@include('modal.ticket.transfer')
	@include('modal.ticket.resolve')

	@endif

	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body table-responsive" style="padding: 25px 30px;">
			<legend class="text-muted">Tickets</legend>
			 <!--Counter Section-->
	        <section id="counter_two" class="counter_two col-sm-12">
	            <div class="overlay" style="border: none;">
	                <div class="container">
	                    <div class="row">
	                        <div class="main_counter_two sections text-center text-muted">
	                            <div>
	                                <div class="row">
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #043D5D;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $total_tickets }}</h2>
	                                            <p>Tickets Generated</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #032E46;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $complaints }}</h2>
	                                            <p>Unresolved Complaints</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #0F595E;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $authored_tickets }}</h2>
	                                            <p>Authored Tickets</p>
	                                        </div>
	                                    </div><!-- End off col-sm-3 -->
	                                    <div class="col-sm-3 col-xs-12" style="padding: 0px 3px;">
	                                        <div class="single_counter_two_right" style="padding: 2px 10px; margin: 2px 5px; background-color: #23B684;color:white;">
	                                            <h2 class="statistic-counter_two">{{ $open_tickets }}</h2>
	                                            <p>Open Tickets</p>
	                                        </div>
	                                    </div>
	                                </div><!-- End off col-sm-3 -->
	                            </div>
	                        </div>
	                    </div><!-- End off row -->
	                </div><!-- End off container -->
	            </div><!-- End off overlay -->
        	</section><!-- End off Counter section -->

			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-bordered table-striped table-condensed" id="ticketTable">
				<thead>
					<th>ID</th>
					<th>Date</th>
					<th>Title</th>
					<th>Type</th>
					<th>Assigned To</th>
					<th>Author</th>
					<th>Status</th>
					<th class="no-sort"></th>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript" src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.counterup.min.js') }}"></script>
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {


		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
		url = `?type=Complaint&status=Open`
		@else
		url = `?type=Complaint`
		@endif

	  	var table = $('#ticketTable').DataTable({
	  		serverSide: true,
	  		processing: true,
  		    order: [[ 0, "desc" ]],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-2'l><'col-sm-3'<'toolbar'>><'col-sm-4'<'filter'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
				"processing": true,
	      ajax: url,
	      columns: [
	          { data: "id" },
	          { data: "parsed_date" },
	          { data: "title" },
	          { data: "type.name" },
	          { data: "staff_name" },
	          { data: "author" },
	          { data: "status"},
	          { data: function(callback){

	          	status = callback.status
	          	left_button_list = `
					<div class="pull-left">
		          		<a href='{{ url("ticket/history") }}" + '/' +  callback.id + "' class='btn btn-md btn-default'>View More
		          		</a>
		          	</div>
		         `

	          	right_button_list = `
						@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
						<button class="assign btn btn-success btn-md">
							<span class="glyphicon glyphicon-share-alt"></span> Assign 
						</button>
						<button class="resolve btn btn-warning btn-md">
							<span class="glyphicon glyphicon-check"></span> Create an Action
						</button>
						@endif	

	          	`;

				@if(Auth::user()->accesslevel == 0)

	          	if(status == 'Open' || status == 'open')
		          	right_button_list += `
						<button class="close-ticket btn btn-danger btn-md">
							<span class="glyphicon glyphicon-off"></span> Close
						</button>

		          	`
		        else if(status == 'Reopen' || status == 'reopen')
		          	right_button_list += `

						<button class="reopen btn btn-info btn-md">
							<span class="glyphicon glyphicon-off"></span> Reopen
						</button>

		          	`
				@endif

				button_list = left_button_list + `<div class="pull-right">` + right_button_list + '</div>'

	          	return button_list;
	          } }
	    	],
	  	});

	 	$("div.toolbar").html(`
			<a id="add" href = '{{ url('ticket/create') }}' class="btn btn-primary btn-sm">
				<span class="glyphicon glyphicon-plus"></span>  Create
			</a>
		`);

		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)

		$('div.filter').html(`
			<span class='text-muted'>Type:</span><div class="btn-group">
			  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tickettype-filter" style="padding: 7px 7px"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="tickettype-button"></span> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" id="tickettype-button">
		   		@foreach($tickettype as $tickettype)
		   		@if($tickettype->name != "Action Taken")
				<li role="presentation">
					<a class="tickettype"  data-name='{{ $tickettype->name }}'>{{ $tickettype->name }}</a>
				</li>
				@endif
			    @endforeach
			  </ul>
			</div>
			<span class='text-muted'>Status:</span><div class="btn-group">
			  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="ticketstatus-filter" style="padding: 7px 7px"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> <span id="ticketstatus-button"></span> <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" id="ticketstatus-button">
			      @foreach($ticketstatus as $ticketstatus)
					<li role="presentation">
						<a class="ticketstatus"  data-name='{{ $ticketstatus }}'>{{ $ticketstatus }}</a>
					</li>
			    @endforeach
			  </ul>
			</div>
		`);

		$('#tickettype-button').text( $('.tickettype:first').text() )
		$('#ticketstatus-button').text( 'Open' )

		$('.tickettype').on('click',function(event)
		{
			$('#tickettype-button').text($(this).data('name'))
			url = "{{ url('ticket') }}" + '?status=' + $('#ticketstatus-button').text() + '&type=' + $('#tickettype-button').text()
			table.ajax.url(url).load();
		})

		$('.ticketstatus').on('click',function(event)
		{
			$('#ticketstatus-button').text($(this).data('name'))
			url = "{{ url('ticket') }}" + '?status=' + $('#ticketstatus-button').text() + '&type=' + $('#tickettype-button').text()
			table.ajax.url(url).load();
		})

	    $('.assign').click( function () {
			id = $(this).data('id')
			$('#transfer-id').val(id)
	    } );

	    $('.resolve').click( function () {
			if(table.row('.selected').data().tickettype == 'Complaint' || table.row('.selected').data().tickettype == 'Maintenance')
			{
				$('#resolve-id').val(table.row('.selected').data().id);
				tag = table.row('.selected').data().tag
				if(tag.indexOf('PC') !== -1 || tag.indexOf('Item') !== -1)
				{
					if(tag.indexOf('PC') !== -1)
					{
						$('#item-tag').val(tag.substr(4))
					}

					if(tag.indexOf('Item') !== -1)
					{
						$('#item-tag').val(tag.substr(6))
					}

					$('#resolve-equipment').show()
				}
				else
				{
					$('#item-tag').val("")
					$('#resolve-equipment').hide()
				}

				$('#resolveTicketModal').modal('show')
			}
			else
			{
				swal('Error!','Only complaints can be resolved','error')
			}

	    } );
	    @endif

		@if(Auth::user()->accesslevel == 0)

	    $('.close-ticket').click( function () {
	    	id = $(this).data('id')
	        swal({
	          title: "Are you sure?",
	          text: "Do you really want to close the ticket?",
	          type: "warning",
	          showCancelButton: true,
	          confirmButtonText: "Yes, close it!",
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
						url: '{{ url("ticket") }}' + "/" + id,
						data: {
							'id': id
						},
						dataType: 'json',
						success: function(response){
							if(response.length > 0){
								swal('Operation Successful','Ticket has been closed','success')
							}else{
								swal('Operation Unsuccessful','Error occurred while closing a ticket','error')
							}

							table.ajax.reload()
						},
						error: function(){
							swal('Operation Unsuccessful','Error occurred while closing a ticket','error')
						}
					});
		          } else {
		            swal("Cancelled", "Operation Cancelled", "error");
	          	}
	        })
	    } );

	    $('.reopen').click( function () {
	    	id = $(this).data('id')
	        swal({
	          title: "Are you sure?",
	          text: "Do you really want to reopen the ticket.",
	          type: "warning",
	          showCancelButton: true,
	          confirmButtonText: "Yes, reopen it!",
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
					type: 'post',
					url: '{{ url("ticket") }}' + "/" + id + '/reopen',
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response.length > 0){
							swal('Operation Successful','Ticket has been reopened','success')
			        		table.ajax.reload().order([ 0, "desc" ]);
						}else{
							swal('Operation Unsuccessful','Error occurred while reopening a ticket','error')
						}
					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while reopening a ticket','error')
					}
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
	    } );
	    @endif

	    // Counter
        jQuery('.statistic-counter_two').counterUp({
            delay: 10,
            time: 2000
        });
    });
</script>
@stop
