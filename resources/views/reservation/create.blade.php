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
					'id'=>'request'
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
		var requestButton = $('#request-btn');
		var selectOption = $('.multi-select');

		var message = {
			error: function (object, errorMessage) {

				// removes all temporary error message
				// by targeting reomvable-object
				$('.removable-object').remove();

				// appends a new error message to the given form
				// alongside the error message
				object.append(
					$('<span />', {
						text: errorMessage,
						class: 'removable-object',
					}),
				);
			},
		};

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

		selectOption.multiselect();

		startTime.clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            // init: function() {
            // 	this.val( moment( this.val() ).format("hh:mmA") );
            // },
            afterDone: function() {
				message.error('Time ended must be greater than time started');
            },
		});

		endTime.clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    fromnow: 1800000,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            // init: function() {
            // 	endTime.val(function () {
			// 		return moment("{{ old('time_end') }}").add("1800000").format("hh:mm A");
			// 	});
            // },
            afterDone: function() {
				message.error('Time ended must be greater than time started');
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

