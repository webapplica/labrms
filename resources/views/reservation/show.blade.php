@extends('layouts.app')

@section('content')
<!-- container -->
<div class="container col-md-offset-3 col-md-6">
    <!-- panel -->
    <div class="panel">
        <!-- panel body -->
        <div class="panel-body">

            @include('reservation.partials.action.approval')

            <legend>
                <h3 
                    class="line-either-side text-muted">{{ _('Reservation Notice') }}</h3>
            </legend>

            <ul class="breadcrumb">
                <li><a href="{{ url('reservation') }}">Reservation</a></li>
                <li class="active">{{ $reservation->id }}</li>
            </ul>

            <h4 
                class="text-justify text-muted" 
                style="font-size: 22px; padding-top: 10px; padding-bottom: 10px;">
                {{ __('reservation.greetings', ['name' => $reservation->user->firstname]) }}
            </h4>
            
            <p class="text-muted">{{ $reservation->conditionAsMessage() }} </p>
            <p class="text-muted"> Reserved Items: @include('reservation.partials.items') </p>  
            <p class="text-muted"> {{ __('reservation.thank_you') }} </p> 
            <p 
                class="text-muted col-md-offset-8 col-md-4 text-left"  
                style="display: block; margin-bottom: 30px;"
                > {{ __('reservation.footer_sincerely') }} <br /> {{ __('reservation.team') }}
            </p> 
        </div>  <!-- panel body -->
        
        <!-- panel footer -->
        <div 
            class="panel-footer"
            style="font-size: 13px;">
            <strong>Reservation Details:</strong> <br />
            <ul class="list-unstyled">
                <li>Reservee : {{ $reservation->user->full_name }}</li>
                <li>Date: {{ $reservation->parsed_date }}</li>
                <li>Time Start : {{ $reservation->parsed_start_time }}</li>
                <li>Time End : {{ $reservation->parsed_end_time }}</li>
                <li>Faculty in-charge : {{ $reservation->accountable }}</li>
            </ul>
        </div> <!-- panel footer -->
    </div> <!-- panel -->
</div> <!-- container -->
@stop
