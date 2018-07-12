@extends('layouts.master-blue')

@section('style')
{{ HTML::style(asset('css/bootstrap-select.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.css') }}" type="text/css">
{{ HTML::style(asset('css/datepicker.min.css')) }}
{{ HTML::style(asset('css/bootstrap-clockpicker.min.css')) }}
{{ HTML::style(asset('css/style.min.css')) }}
<style>
	#page-body, #hide,#hide-notes,#reservation-info{
		display:none;
	}
	.panel-padding{
		padding: 10px;
	}
	.datepicker{z-index:1151 !important;}
</style>
@stop
@section('script-include')
{{ HTML::script(asset('js/moment.min.js')) }}
{{ HTML::script(asset('js/datepicker.min.js')) }}
{{ HTML::script(asset('js/datepicker.en.js')) }}
{{ HTML::script(asset('js/bootstrap-clockpicker.min.js')) }}
{{ HTML::script(asset('js/bootstrap-select.min.js')) }}
@stop
@section('content')
<div class="col-md-offset-3 col-md-6 panel" id="reservation">
	<div class="panel-body">
		<legend>
			<h3 style="color:#337ab7;">Reservation Form
				<div class="btn-group pull-right">
					<div class="btn-group">
					{{ Form::button('Show Rules',[
						'class'=>'btn btn-sm btn-primary',
						'id' => 'show-notes',
						'data-toggle'=>'modal',
						'data-target' => '#reservationRulesModal'
					]) }}
					</div>
					<div class="btn-group">
					{{ Form::button('Search Items Availability',[
						'class'=>'btn btn-sm btn-default',
						'id' => 'show',
						'data-toggle'=>'modal',
						'data-target' => 'reservationCalendarModal'
					]) }}
					</div>
				</div>
			</h3>
		</legend>
		@include('errors.alert')
		<p class="text-primary">.
			<strong>Note: </strong>3 day rule is not applied for your reservation
		</p>
		<form class="form-horizontal" method="post" action="{{ url('reservation') }}" id="reservationForm">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<!-- creator name -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('name','Faculty-in-charge') }}
				</div>
				<div class="col-sm-9">
				{{
					Form::select('name',[],Input::old('name'),[
					'id'=>'name',
					'class'=>'form-control'
				]) }}
				</div>
			</div>
			<!-- date of use -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('dateofuse','Date of Use',[
					'data-language'=>"en"
				]) }}
				</div>
				<div class="col-sm-9">
				{{ Form::text('dateofuse',Input::old('dateofuse'),[
					'id' => 'dateofuse',
					'class'=>'form-control',
					'placeholder'=>'MM | DD | YYYY',
					'readonly',
					'style'=>'background-color: #ffffff	'
				]) }}
				</div>
			</div>
			<!-- time started -->
			<div class="form-group" id="time-start-group">
				<div class="col-sm-3">
				{{ Form::label('time_start','Time started') }}
				</div>
				<div class="col-sm-9">
				{{ Form::text('time_start',Input::old('time_start'),[
					'class'=>'form-control',
					'placeholder'=>'Hour : Min',
					'id' => 'starttime',
					'readonly',
					'style'=>'background-color: #ffffff	'
				]) }}
				<span id="time-start-error-message" class="text-danger" style="font-size:10px;"></span>
				</div>
			</div>
			<!-- time_end -->
			<div class="form-group" id="time-end-group">
				<div class="col-sm-3">
				{{ Form::label('time_end','Time end') }}
				</div>
				<div class="col-sm-9">
				{{ Form::text('time_end',Input::old('time_end'),[
					'class'=>'form-control background-white',
					'placeholder'=>'Hour : Min',
					'id' => 'endtime',
					'readonly',
					'style'=>'background-color: #ffffff	'
				]) }}
				<span id="time-end-error-message" class="text-danger" style="font-size:10px;"></span>
				</div>
			</div>
			<!-- Item type -->
			<div class="form-group">
				<div class="col-xs-3">
					{{ Form::label('itemtype','Items') }}
				</div>
				<div class="col-xs-9"> 
					{{ Form::select('items[]', $items,Input::old('items'),[
						'id' => 'items',
						'class'=>'form-control'
					]) }}
				</div>
			</div>
			<!-- Location -->
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('location','Location') }}
				</div>
				<div class="col-sm-9">
				{{
					Form::select('location', $rooms,Input::old('location'),[
					'id'=>'location',
					'class'=>'form-control'
				]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3">
				{{ Form::label('purpose','Purpose') }}
				</div>
				<div class="col-sm-9">
				{{ Form::select('purpose', $purposes,Input::old('purpose'),[
					'id' => 'purpose',
					'class'=>'form-control'
				]) }}
				<div class="checkbox">
					<label>
						<input type="checkbox" name="contains" id="contains"> Not in the list?
					</label>
				</div>
				{{ Form::textarea('description',Input::old('description'),[
					'id' => 'description',
					'class'=>'form-control',
					'placeholder'=>'Enter  details here...',
					'style' => 'display:none;'
				]) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<p class="text-muted text-justified">
						By clicking the request button, you agree to CCIS - LOO Terms and Conditions regarding reservation and lending equipments. <span class="text-danger"> The information filled up will no longer be editable and is final.</span>
					</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::button('Request',[
					'class'=>'btn btn-lg btn-primary btn-block',
					'id'=>'request'
				]) }}
				</div>
			</div>
		</form>
	</div>
</div>
@stop
@section('script')	
<script>
	$(document).ready(function(){

		$('#contains').change(function(){
			$('#purpose').toggle(200)
			$('#description').toggle(200)
		})

		$('#show').click(function(){
			$('#reservationCalendarModal').modal('show');
		});

		$("#dateofuse").datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function(){
				$('#dateofuse').val(moment($('#dateofuse').val(),'MM/DD/YYYY').format('MMMM DD, YYYY'))
			}
		});

		$("#dateofuse").val(moment('{{ $date }}').format('MMM DD, YYYY'));

		$('#starttime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    // autoclose: true,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
            	$('#starttime').val(moment().format("hh:mmA"))
            },
            afterDone: function() {
            	error('#time-start-error-message','*Time started must be less than time end')
            },
		});

		$('#endtime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    // autoclose: true,
		    fromnow: 1800000,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function(){
            	$('#endtime').val(moment().add("1800000").format("hh:mmA"))
            },
            afterDone: function() {
            	error('#time-end-error-message','*Time ended must be greater than time started')
            },
		});

		function error(attr2,message){
			if($('#endtime').val()){
				if(moment($('#starttime').val(),'hh:mmA').isBefore(moment($('#endtime').val(),'hh:mmA'))){
					$('#request').show(400);
					$('#time-end-error-message').html(``)
					$('#time-start-error-message').html(``)
					$('#time-end-group').removeClass('has-error');
					$('#time-start-group').removeClass('has-error');
				}else{
					$('#request').hide(400);
					$(attr2).html(message).show(400)
					$('#time-end-group').addClass('has-error');
					$('#time-start-group').addClass('has-error');
				}
			}
		}

		$('#request').click(function(){
			swal({
			  title: "Are you sure?",
			  text: "By submitting a request, you acknowledge our condition of three(3) working days in item reservation unless there is a special event or non-working holidays. Disregarding this notice decreases your chance of approval",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			  confirmButtonText: "Yes, submit it!",
			  cancelButtonText: "No, cancel it!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function(isConfirm){
			  if (isConfirm) {
					$("#reservationForm").submit();
			  } else {
			    swal("Cancelled", "Request Cancelled", "error");
			  }
			});
		});

		$('#items').on('rendered.bs.select', function (e) {
			$('#page-body').show();
			
			@if( Session::has("success-message") )
			  swal("Success!","{{ Session::pull('success-message') }}","success");
			@endif
			@if( Session::has("error-message") )
			  swal("Oops...","{{ Session::pull('error-message') }}","error");
			@endif
		});
	});
</script>
@stop

