@extends('layouts.app')

@section('content')
<div class="col-md-offset-3 col-md-6 panel">
	<div class="panel-body">

		<legend>
			<h3 class="text-muted">Reservation Form</h3>
		</legend>

		@include('errors.alert')
		<form 
			method="post" 
			action="{{ url('reservation') }}" 
			id="reservation-form"
			class="form-horizontal"
			data-confirmation-title="{{ __('reservation.confirmation_title') }}"
			data-confirmation-message="{{ __('reservation.confirmation_message') }}"
			data-before-submission-notice="{{ __('reservation.notice_before_submission') }}"
			data-suggested-date="{{ $suggestedDate }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			
			@include('reservation.partials.create.form')

			<div class="form-group">
				<div class="col-sm-12">
				{{ Form::button('Request',[
					'class'=>'btn btn-lg btn-primary btn-block',
					'id'=>'request-btn'
				]) }}
				</div>
			</div>
		</form>
	</div>
</div>
@stop

@section('scripts-include')	
<script type="text/javascript">
	$(document).ready(function() {

		var form = $('#reservation-form');
		var reservationDate = $('#date');
		var startTime = $('.startTime');
		var endTime = $('.returnTime');
		var startTimeInput = $('#startTime');
		var returnTimeInput = $('#returnTime');
		var requestButton = $('#request-btn');
		var selectOption = $('.multi-select');

		var message = {
			error: function (object, errorMessage) {
				
				// removes all temporary error message
				// by targeting reomvable-object
				message.remove();

				// appends a new error message to the given form
				// alongside the error message
				object.append(
					$('<span />', {
						text: errorMessage,
						class: 'removable-object',
					}),
				);
			},

			remove: function () {

				// removes all temporary error message
				// by targeting reomvable-object
				$('.removable-object').remove();
			}
		};
		
		// checks if the current time start is less than
		// the end time
		function checkIfTimeStartIsBeforeEndTime()
		{
			parsedStartTime = moment(startTimeInput.val(), 'hh:mm A');
			parsedEndTime = moment(returnTimeInput.val(), 'hh:mm A'); 	

			if(! parsedStartTime.isBefore(parsedEndTime)) {
				message.error(startTime.parent('div'), 'Time start must start before time end');
			} 

			else {
				message.remove();
			}
		}

		// toggles the select button and textarea on 
		// check of checkbox corresponding to the form
		$('#has-purpose-checkbox').change(function() {
			$('#purpose-select').toggle(200)
			$('#description-textarea').toggle(200)
		});

		reservationDate.datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function() {
				parsedDate = moment(reservationDate.val(),'MM/DD/YYYY').format('MMMM DD, YYYY');
				reservationDate.val(parsedDate);
			},
		});

		reservationDate.val(function() {
			suggestedDate = form.data('suggested-date');
			return moment(suggestedDate).format('MMM DD, YYYY');
		});

		selectOption.selectpicker();

		startTime.clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            afterDone: function() {
				checkIfTimeStartIsBeforeEndTime();
            },
		});

		endTime.clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    fromnow: 1800000,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            afterDone: function() {
				checkIfTimeStartIsBeforeEndTime();
            },
		});

		requestButton.on('click', function() {

			// create a alert message
			// before submitting the form
			swal( {
				title: form.data('confirmation-title'),
				text: form.data('confirmation-message'),
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, submit it!",
				cancelButtonText: "No, cancel it!",
				closeOnConfirm: false,
				closeOnCancel: false
			}, 
			
			function(isConfirm) {
				if (isConfirm) {
					form.submit();
				} else {
					notify.error('Request cancelled', 'Cancelled');
				}
			} );
		});
	});
</script>
@stop

