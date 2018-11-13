@extends('layouts.app')

@section('content')
<div class="container col-sm-offset-3 col-sm-6 panel panel-body">
    <legend>
        <h3 class="text-muted">Academic Year: Create</h3>
    </legend>

    <ol class="breadcrumb">
        <li><a href="{{ url('academicyear') }}">Academic Year</a></li>
        <li class="active">Create</li>
    </ol>

    @include('errors.alert')

    <form 
        method="post" 
        action="{{ url('academicyear') }}" 
        id="academic-year-form"
        data-suggested-date="{{ $currentDate }}"
        data-end-date="{{ $endDate }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        

        <div class="form-group">
            <label for=start>Start of the Academic Year</label>
            {{ Form::text('startOfYear', old('startOfYear'), [
                'class' => 'form-control',
                'id' => 'start-of-year',
                'style' => 'background-color: white;',
                'readonly',
                'placeholder' => 'Academic Year Start'
            ]) }}
        </div>

        <div class="form-group">
            <label for=start>End of the Academic Year</label>
            {{ Form::text('endOfYear', old('endOfYear'), [
                'class' => 'form-control',
                'id' => 'end-of-year',
                'style' => 'background-color: white;',
                'readonly',
                'placeholder' => 'Academic Year End'
            ]) }}
        </div>

        <div class="form-group">
            <button 
                type="submit" 
                class="btn btn-primary btn-block">Submit</button>
        </div>
    </form>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
        var datepicker = $('.datepicker');
        var form = $('#academic-year-form');
        var start = $('#start-of-year');
        var end = $('#end-of-year');

        // initialize datepicker for the 
        // start of the year form
		start.datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function() {
				parsedDate = moment(start.val(),'MM/DD/YYYY').format('MMMM DD, YYYY');
				start.val(parsedDate);
			},
		});

        // initialize datepicker for the 
        // end of the year form 
		end.datepicker({
			language: 'en',
			showOtherYears: false,
			todayButton: true,
			minDate: new Date(),
			autoClose: true,
			onSelect: function() {
				parsedDate = moment(end.val(),'MM/DD/YYYY').format('MMMM DD, YYYY');
				end.val(parsedDate);
			},
		});
        
        // initialize the value for the 
        // start of the year form
		start.val(function() {
			suggestedDate = form.data('suggested-date');
			return moment(suggestedDate).format('MMMM DD, YYYY');
		});

        // initialize the value for the 
        // end of the year form
		end.val(function() {
			suggestedDate = form.data('end-date');
			return moment(suggestedDate).format('MMMM DD, YYYY');
		});
</script>
@endsection