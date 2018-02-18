@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-md-offset-3 col-md-6'>
		<div class="panel panel-body" style="padding-top: 20px;padding-left: 40px;padding-right: 40px;">

	 		{{ Form::open(['method'=>'post','route'=>'inventory.store','class'=>'form-horizontal','id'=>'inventoryForm']) }}

			<legend><h3 style="color:#337ab7;"><span id="form-name">Inventory</span></h3></legend>
			<ul class="breadcrumb">
				<li><a href="{{ url('inventory') }}">Item Inventory</a></li>
				<li class="active">Add</li>
			</ul>

			@include('errors.alert')

			<div class="form-group">
				<div class="col-sm-12">
					<label>Receipt Number</label>
					<input type="text"  class="form-control" placeholder="Receipt Number" name="receipt" value="{{ old('receipt') }}" id="receipt" />
					<div id="receipt-existing" class="col-sm-12"></div>
				</div>
			</div>

			@include('inventory.item.form')

			<div class="form-group">
				<div class="col-sm-12">
					<button type="submit" value="create" name="action" id="submit" class="btn btn-lg btn-primary btn-flat btn-block"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit </button>
				</div>
			</div>

			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function(){

		$('#receipt').autocomplete({
			source: "{{ url('receipt') }}"
		})

		$('#receipt').on('change', function(){
			receipt = $('#receipt').val()

			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'get',
                url: "{{ url('receipt') }}" + '/' + receipt,
				dataType: 'json',
				success: function(response){
					if(response.length)
					{
						$('#receipt-existing').html("<p class='text-success'>Receipt Exists</p>")
					} else {
						$('#receipt-existing').html("<p class='text-warning'>Receipt Doesn't Exists Yet. This will create a new receipt</p>")
					}
				}
			})
			
		})

		$('#brand #itemtype #model').on('change',function(){
			url = "{{ url('get') }}" + '/' + $('#itemtype').val() + '/' + $('#brand').val() + '/' + $('#model').val()
			setValue(url)
		});

		$('#brand').trigger('change');

		function setValue(_url)
		{
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'get',
                url: _url,
				dataType: 'json',
				success: function(response){
					if(response != 'error')
					{
						$('#alert-existing').html("<div class='alert alert-success'><strong>Item exists!</strong> The quantity you inputted will be appended to the existing item</div>")
						$('#details').val(response.details)
						$('#unit').val(response.unit)
						$('#warranty').val(response.warranty)
					} else {
						$('#alert-existing').html("<div class='alert alert-warning'><strong>Warning!</strong>Item doesn't exist yet. This will create a new item</div>")
					}
				}
			})
		}
	})
</script>
@stop
