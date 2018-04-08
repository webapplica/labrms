@extends('layouts.master-blue')
@section('style-include')
{{ HTML::style(asset('css/jquery.sidr.light.min.css')) }}
{{ HTML::style(asset('css/sidr-style.min.css')) }}
@stop
@section('script-include')
{{ HTML::script(asset('js/jquery.sidr.min.js')) }}
{{ HTML::script(asset('js/jquery.hideseek.min.js')) }}
<script src="{{ asset('js/jQuery.succinct.min.js') }}"></script>
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	a > hover{
		text-decoration: none;
	}

	th , tbody{
		text-align: center;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Inventory</h3></legend>
			@if( Session::has('success')  )
			 <div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<ul class="list-unstyled" style='margin-left: 10px;'>

						<li class="text-capitalize"><span class="glyphicon glyphicon-ok"></span> {{ Session::pull('success') }}</li>
					</ul>
				</div>
			@endif
			<table class="table table-hover table-striped table-bordered table-condensed" id="inventoryTable">
				<thead>
					<th class="col-md-1">ID</th>
					<th class="col-md-1">Model</th>
					<th class="col-md-1">Brand</th>
					<th class="col-md-1">Type</th>
					<th class="col-md-2">Details</th>
					<th class="col-md-1">Unit</th>
					<th class="col-md-1">Quantity</th>
					<th class="col-md-1">Unprofiled</th>
					<th class="col-md-3 no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
<script type="text/javascript">
	$(document).ready(function() {

	    var table = $('#inventoryTable').DataTable({
			"pageLength": 100,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
			language: {
					searchPlaceholder: "Search..."
			},
			"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f><'search-bar'>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			ajax: "{{ url('inventory') }}",
			columns: [
					{ data: "code" },
					{ data: "model" },
					{ data: "brand" },
					{ data: "itemtype.name" },
					{ data: "details" },							
					{ data: "unit_name" },
					{ data: "quantity" },
					{ data: "unprofiled" },
					{ data: function(callback){
						return `
							<a href="{{ url('inventory') }}/`+callback.id+`/log" class="btn btn-primary btn-sm" type="button">
								<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
								<span class="hidden-sm hidden-xs">Logs</span>
							</a>
							<a href="{{ url('item/profile/create?id=') }}`+callback.id+`" id="profile" class="btn btn-success btn-sm" type="button">
								<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
								<span class="hidden-sm hidden-xs">Profile</span>
							</a>
							<a href="{{ url('item/profile') }}/`+callback.id+`" id="view" class="btn btn-sm btn-default" type="button">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
								<span class="hidden-sm hidden-xs">View</span>
							</a>
						`
					}}
			],
	    });

	 	$("div.toolbar").html(`
				<button id="new" class="btn btn-md btn-primary">
					<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					<span id="nav-text">Add</span>
				</button>
		`);

	 	$('div.search-bar').html(`
	 		<div class="col-sm-12">
				<a href="{{ url('inventory/search') }}" class="pull-right" style="font-size:10px;text-decoration:none;">Advance Search</a>
			</div>
 		`)

	    $('[data-toggle="popover"]').popover();

	    $('.truncate').succinct({
	        size: 20
	    });

	    $('#new').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('inventory/create') }}"
											 + '?brand=' + table.row('.selected').data().brand
											 + '&' +'model=' + table.row('.selected').data().model
											 + '&' +'itemtype=' + table.row('.selected').data().itemtype.name
				}
			}catch( error ){
				window.location.href = "{{ url('inventory/create') }}"
			}
	    })

	    $('#edit').on('click',function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
					window.location.href = "{{ url('inventory') }}" + '/' + table.row('.selected').data().id + '/edit'
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    })
	} );
</script>
@stop
