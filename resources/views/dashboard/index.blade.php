@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="col-md-3" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel">
			<div class="panel-body">
			    <h4 class="line-either-side text-muted">
		      		Reservation
		      	</h4>
				<div id="reservation-list">
				</div>
			</div>
        </div> <!-- end of reservation tab -->
	</div>
	<div class=" col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Tickets</h3>
			</div>
			<div class="list-group">
				@foreach($tickets as $ticket)
				<a href="{{ url('ticket/' . $ticket->id) }}" class="list-group-item">
					<h4 class="list-group-item-heading">{{ $ticket->title }}</h4>
					<p class="list-group-item-text">{{ $ticket->details }}</p>
				</a>
				@endforeach
			</div>
			{{ $tickets->links() }}
        </div> <!-- end of activities tab -->
	</div>
	<div class="col-md-3" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel">
			<div class="panel-body">
			    <h4 class="line-either-side text-muted">
		      		Lent Items
		      	</h4>
				<div id="lentitems-list">
				</div>
			</div>
        </div> <!-- end of lent tab -->
	</div>
</div>
@stop