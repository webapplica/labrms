@extends('layouts.app')

@section('content')
<div class="col-md-offset-3 col-md-6 panel">
	<div class="panel-body">
		@include('errors.alert')
		<form 
			class="form-horizontal" 
			method="post" 
			action="{{ url('reservation') }}" 
			id="reservation-form"
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

		$('#has-purpose-checkbox').change(function() {
			$('#purpose-select').toggle(200)
			$('#description-textarea').toggle(200)
		})

		$("#date").datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function() {
				date = $('#date');
				parsedDate = moment(date.val(),'MM/DD/YYYY').format('MMMM DD, YYYY');
				date.val(parsedDate);
			},
		});

		$("#date").val(function() {
			suggestedDate = $('#reservation-form').data('suggested-date');
			return moment(suggestedDate).format('MMM DD, YYYY');
		});

		$('#startTime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function() {
            	this.val( moment( this.val() ).format("hh:mmA") );
            },
            afterDone: function() {
            	message.time.error();
            },
		});
		
    	message.time.error();

		$('#returnTime').clockpicker({
		    placement: 'bottom',
		    align: 'left',
		    fromnow: 1800000,
		    default: 'now',
            donetext: 'Select',
            twelvehour: true,
            init: function() {
            	$('#endtime').val(moment({{ old('time_end') }}).add("1800000").format("hh:mm A"))
            },
            afterDone: function() {
            	error('#time-end-error-message','*Time ended must be greater than time started')
            },
		});

		var message = {
			time: {
				error: function () {
					console.log('error occured');
				},
			}
		}

		function error(attr2, message) {
			if($('#endtime').val()){
				if(moment($('#starttime').val(),'hh:mmA').isBefore(moment($('#endtime').val(),'hh:mmA'))){
					$('#request-btn').show(400);
					$('#time-end-error-message, #time-start-error-message').html(``)
					$('#time-end-group, #time-start-group').removeClass('has-error');
				}else{
					$('#request').hide(400);
					$(attr2).html(message).show(400)
					$('#time-end-group, #time-start-group').addClass('has-error');
				}
			}
		}

		$('#request-btn').click(function() {
			swal({
			  title: "Submitting the form",
			  text: "Are you really done filling up all the data? This data is no longer editable",
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
					$("#reservation-form").submit();
			  } else {
			    swal("Cancelled", "Request Cancelled", "error");
			  }
			});
		});
	});
</script>
@stop

