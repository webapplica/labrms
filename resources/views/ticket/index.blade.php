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
			@include('errors.alert')
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

		url = window.location.href

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
			<a id="add" href = '{{ url('ticket/create') }}' class="btn btn-primary btn-sm">
				<span class="glyphicon glyphicon-plus"></span>  Create
			</a>
		`);

		@if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)

		$('div.filter').html(`
			<span class='text-muted'>Type:</span>
			<select name="type" class="form-control ticket-filter" id="ticket-type">
				<option value="all">All</option>
				@foreach($ticket_types as $t)
				<option value="{{ $t->name }}" @if( $type == $t->name) selected @endif>
					{{ $t->name }}
				</option>
				@endforeach
			</select>
			<span class='text-muted'>Status:</span>
			<select name="status" class="form-control ticket-filter" id="ticket-status">
				@foreach($ticket_statuses as $stat)
				<option value="{{ $stat }}" @if( $status == $stat) selected @endif>
					{{ $stat }}
				</option>
				@endforeach
			</select>
		`);

		$('.ticket-filter').on('change',function(event)
		{
			url = setUrl()
			table.ajax.url(url).load();
		})

		function setUrl()
		{

			type = $('#ticket-type').val()
			status = $('#ticket-status').val()
			url = "{{ url('ticket') }}" + '?status=' + status + '&type=' + type
			history.pushState(null, '', url);
			return url
		}

	    $('#ticketTable').on( 'click', '.resolve', function () {
	    	id = $(this).data('id')
	    	type = $(this).data('type')
	    	tag = $(this).data('tag')
			if(type == 'Complaint' || type == 'Maintenance')
			{
				$('#resolve-id').val(id);
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

	    // Counter
        jQuery('.statistic-counter_two').counterUp({
            delay: 10,
            time: 2000
        });
    });
</script>
@stop
