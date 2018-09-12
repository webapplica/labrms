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
		<div class="panel panel-primary">
			<div class="panel-body">
			    <h4 class="line-either-side text-muted">
		      		Activity
		      	</h4>
				<div id="ticket-list">
				</div>
			</div>
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