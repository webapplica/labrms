@extends('layouts.master-blue')

@section('style')

<link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/selectize.bootstrap3.css') }}" type="text/css">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap-clockpicker.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('css/style.min.css') }}">
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
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/loadingoverlay.min.js') }}"></script>
<script src="{{ asset('js/loadingoverlay_progress.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/datepicker.en.js') }}"></script>
<script src="{{ asset('js/bootstrap-clockpicker.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
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
					<select name="name" id="name" class="form-control">
					@if( !empty($faculties))
						@foreach($faculties as $faculty)
						<option value="{{ $faculty->id }}" {{ $faculty->id == old('name') ? 'selected' : "" }}>{{ $faculty->full_name }}</option>
						@endforeach
					@endif
					</select>	
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
				{{ Form::text('dateofuse', old('dateofuse'),[
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
				{{ Form::text('time_start', old('time_start'),[
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
				{{ Form::text('time_end', old('time_end'),[
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
					{{ Form::label('items','Items') }}
				</div>
				<div class="col-xs-9"> 
					{{ Form::select('items[]', $items, old('items'),[
						'id' => 'items',
						'class'=>'form-control',
						'multiple'
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
					Form::select('location', $rooms, old('location'),[
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
				{{ Form::select('purpose', $purposes, old('purpose'),[
					'id' => 'purpose',
					'class'=>'form-control'
				]) }}
				<div class="checkbox">
					<label>
						<input type="checkbox" name="contains" id="contains"> Not in the list?
					</label>
				</div>
				{{ Form::textarea('description',old('description'),[
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
	});
</script>
@stop

