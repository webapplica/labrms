@extends('layouts.master-blue')

@section('style')
{{ HTML::style(asset('css/jquery-ui.min.css')) }}
{{ HTML::style(asset('css/style.css')) }}
<style>
	#page-body,#page-two{
		display: none;
	}
	.panel-padding{
		padding: 10px;
	}
</style>
@stop

@section('script-include')
{{ HTML::script(asset('js/jquery-ui.js')) }}
@stop

@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-offset-3 col-md-6 panel panel-body">
		{{ Form::open(['method'=>'post','url'=>'item/profile','class'=>'form-horizontal','id'=>'profilingForm']) }}
		<legend><h3 class="text-muted">Item Profile</h3></legend>
		<ol class="breadcrumb">
		  <li><a href="{{ url('inventory') }}">Item Inventory</a></li>
		  <li class="active">Create</li>
		</ol>
		@if( Session::has('success')  )
		 <div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<ul class="list-unstyled" style='margin-left: 10px;'>

					<li class="text-capitalize"><span class="glyphicon glyphicon-ok"></span> {{ Session::pull('success') }}</li>
				</ul>
			</div>
		@endif
	    @include('errors.alert')
		<div class="col-md-12">
			<p class="text-muted">
				<span class="">Last Profiled Item: {{ $lastprofiled }}</span>
				<span class="pull-right">Unprofiled Items:<span id="total"></span></span>
			</p>
		</div>
		<div class="panel panel-body">
			<div id="page-one">
				<!-- item name -->
				<div class="form-group">
					<div class="col-sm-12">
						<label for="inventory_id">
							Inventory Name
						</label>
						{{ Form::text('inventory_name',"$inventory->brand-$inventory->model-".$inventory->itemtype->name,[
							'class' => 'form-control',
							'placeholder' => "$inventory->brand-$inventory->model" ,
							'readonly',
							'style'=>'background-color:white;'
						]) }}
						<input type="hidden" value="{{ $inventory->id }}" name="inventory_id" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('receipt_id','Acknowledgement Receipt') }}
					{{ Form::select('receipt_id', $receipts,Input::old('receipt_id'),[
						'class' => 'form-control readonly-white',
						'style'=>'background-color:white;'
					]) }}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('date','Date Received') }}
					<input id="dateReceived" class="form-control" placeholder="Date Received" readonly="readonly" name="datereceived" type="text" style="background-color: white;">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12" id="quantity-to-profile-form">
					{{ Form::label('quantity','Quantity To Profile') }}
					{{ Form::number('quantity', old('quantity'),[
						'id' => 'quantity',
						'class' => 'form-control',
						'placeholder' => 'Quantity To Profile',
						'min' => '1',
						'required'
					]) }}

					<input type="checkbox" id="lock-quantity" value="1" {{ old('lock-quantity') ? 'checked' : '' }} name="lock-quantity"> Lock? 
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
					{{ Form::label('location','Location') }}
					<select name="location" id="location" class="form-control">
					@foreach($locations as $key=>$value)
						<option {{ (old('location') == $key) ? 'selected=selected' : ($value == 'Server') ? 'selected=selected' : null }} value="{{ $key }}">{{ $value }}</option>
					@endforeach
					</select>
					<p class="text-muted pull-right" style="font-size:10px;"><span class="text-danger">Note:</span> The Default Storage Location is <strong>Server Room</strong></p>
					</div>
				</div>
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				  <div class="panel panel-default">
				    <div class="panel-heading" role="tab" id="headingOne">
				      <h4 class="panel-title">
				        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="text-decoration: none;">
				          Property Number Generator <span class="pull-right glyphicon glyphicon-triangle-bottom"></span>
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
				      <div class="panel-body">
				      	<p class="text-muted">If you have constant, incrementing value for property number, use this to fill the propertynumber of the each</p>
				        <div class="form-group">
				        	<div class="col-sm-12">
					        	{{ Form::label('propertynumber-assitant','Property Number Constant Value Fillers:') }}
					        	<p class="text-muted" style="font-size: 12px;">
					        		This will fill up the property number whatever constant value it contains
					        	</p>
					        	<input type="text" class="form-control" id="propertynumber-assitant" placeholder="PUP-0000-0000" />
					        </div>
				        </div>
				        <div class="form-group">
				        	<div class="col-sm-12">
					        	<p class="text-muted" style="font-size: 12px;">
					        		Note: This will append number after the constant value you filled up
					        	</p>
					        	<input type="checkbox" id="is-incrementing" /> Is Incrementing?
					        	<input type="number" placeholder="Starting Value" class="form-control" id="is-incrementing-value" disabled />
					        </div>
				        </div>
				      </div>
				    </div>
				  </div>
				</div>
				<div class="form-group pull-right">
						<div class="col-md-12">
							<button type="submit" id="next" class="btn btn-md btn-primary" style="padding-left: 20px;padding-right: 20px;">Next</button>
						</div>
				</div>
			</div>
			<div id="page-two">
				<table id="itemTable" class="table table-bordered table-hover">
						<thead>
							<th>ID</th>
							<th>Property Number</th>
							<th>Serial ID</th>
						</thead>
						<tbody>
						</tbody>
				</table>
				<div class="form-group pull-right">
					<div class="col-md-12">
						<button type="button" id="previous" class="btn btn-md btn-default">Previous</button>
					{{ Form::submit('Profile',[
						'class'=> 'btn btn-md btn-primary',
						'name' => 'Profile'
					]) }}
					</div>
				</div>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function(){

		$('#lock-quantity').trigger('click')

		$('#lock-quantity').on('click', function(){
			if($(this).is(':checked')) {
				$('#quantity').prop('readonly', 'readonly')
			}else {
				$('#quantity').prop('readonly', '')

			}
		})

		$('#next').on('click',function(event){
			event.preventDefault()
			quantity = $('#quantity').val()
			if ( quantity == "" ){
				swal('Error Occurred!','Quantity must be greater than zero','error')
			} else if ( quantity == null ){
				swal('Error Occurred!','Quantity must be greater than zero','error')
			} else if ( quantity == 0 ){
				swal('Error Occurred!','Quantity must be greater than zero','error')
			} else if( quantity > parseInt( $('#total').text() ) ) {
				swal('Error Occurred!','Quantity must not be greater than unprofiled items','error')
			} else if( quantity > 30 ) {
				swal('Error Occurred!','Batch process has a limit of 30 items only!','error')
			} else {
				pageOne()
			}
		});

		$('#is-incrementing').on('change',function(){
			if($('#is-incrementing').is(':checked'))
			{

				$('#is-incrementing-value').removeProp('disabled')
			} else {
				$('#is-incrementing-value').prop('disabled','disabled')
			}
		})

		$('#quantity').on('focusin change keyup keypress',function(){
			quantity = $('#quantity').val()

			if(quantity > 30)
			{
					$('#quantity-error').remove()
					$('#quantity').after('<p class="text-danger" id="quantity-error" style="font-size: 12px;">Warning! The system accepts profile up to 30 items only.</p>')

				$('#quantity').val(30)
			}

			if( quantity == "" )
			{
					$('#quantity-error').remove()
					$('#quantity').after('<p class="text-danger" id="quantity-error" style="font-size: 12px;">Warning! This is a required field.</p>')
			}

			if( !isNaN(quantity) && quantity != "" && quantity <= 0 )
			{
					$('#quantity-error').remove()
					$('#quantity').after('<p class="text-danger" id="quantity-error" style="font-size: 12px;">Warning! Must have a minimum quantity of one(1).</p>')

				$('#quantity').val(1)
			}

			if( quantity <= 30 && quantity >= 1 ) {

				$('#quantity-error').fadeOut(400,function(){ $(this).remove() })
			}
		})

		function pageOne()
		{
			$('#page-one').hide(400);
			$('#page-two').show(400);

			if(! $('#lock-quantity').is(':checked') || $('tbody tr').length == 0)
			{
				$('tbody').html("");

				const1 = "";
				if($('#propertynumber-assitant').val() != "")
				{
					const1 = $('#propertynumber-assitant').val()
				}

				const2 = "";
				if($('#is-incrementing').is(":checked"))
				{
					const2 = $('#is-incrementing-value').val()
				}

				quantity = $('#quantity').val();
				for( var ctr = 1 ; ctr <= quantity ; ctr++ ){
					insertForm(ctr,const1,const2);

					if($('#is-incrementing').is(":checked"))
					{
						const2++
					}
				}
			}

		}

		function pageTwo()
		{
			$('#page-two').hide(400);
			$('#page-one').show(400);
		}

		$('#previous').on('click',function(){
			pageTwo()
		});

	    function insertForm(row,const1 = "",const2 = "")
	    {
	      $('tbody').append(`
				<tr>
					<td>`+row+`</td>
					<td>
						<input type="text" name="item[`+(row-1)+`][propertynumber]" class="form-control" placeholder="Property Number" value="`+ const1 + const2 + `">
					</td>
					<td>
						<input type="text" name="item[`+(row-1)+`][serialid]" class="form-control" placeholder="Serial Number">
					</td>
				</tr>
	      `)
	    }

		$('#propertynumber').on('focus',function(){
			var current = $('#propertynumber').val()
			var constant = "PUP-";
			$('#propertynumber').val( constant + current )
		});

		$( "#dateReceived" ).datepicker({
			  changeMonth: true,
			  changeYear: false,
			  maxAge: 59,
			  minAge: 15,
		});

		$('#inventory-help').click(function(){
			$('#inventory-help').popover('show')
		});

		@if(Input::old('dateReceived'))
			$('#dateReceived').val({{ Input::old('dateReceived') }});
			setDate("#dateReceived");
		@else
			$('#dateReceived').val("{{ Carbon\Carbon::now()->toFormattedDateString() }}");
			setDate("#dateReceived");
		@endif

		$('#dateReceived').on('change',function(){
			setDate("#dateReceived");
		});

		$

		function setDate(object){
				var object_val = $(object).val()
				var date = moment(object_val).format('MMM DD, YYYY');
				$(object).val(date);
		}

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
            url: '{{ url("inventory/$inventory->id") }}' ,
			dataType: 'json',
			success: function(response){
				$('#total').text(parseInt(response.unprofiled));
			}
		})

		$('#page-body').show();
	});
</script>
@stop
