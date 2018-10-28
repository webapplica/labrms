@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="col-md-offset-3 col-md-6">
		<div class="panel panel-body">
            <h4 
                class="line-either-side text-muted" 
                style="font-size: 24px;">Reservation Notice</h4>
            <h4 
                class="text-justify text-muted" 
                style="font-size: 22px;">
                <span class="pull-left">
                        Hi 
                        <strong> 
                            {{ $reservation->user->firstname }} </strong>!
                </span>

                @if($reservation->approval == 0)
                    <span class="pull-right">
                        <button 
                            data-id="`{{ $reservation->id }}`" 
                            id="approve" 
                            class="btn btn-xs btn-success">
                            <i class="fa fa-thumbs-o-up fa-2x" aria-hidden="true"></i>
                        </button>
                        <button 
                            id="disapprove" 
                            data-id="`{{ $reservation->id }}`" 
                            data-reason="`{{ $reservation->remark }}"  
                            class="btn btn-xs btn-danger">
                            <i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i>
                        </button>
                    </span>
                @endif
      		</h4>
            <div class="clearfix"></div>
      		<p class="text-muted">
                We have received your reservation request and we would like to notify you that your request has been 
                  
                @if($reservation->approval == 1)
                    <span class="label label-success">
                        Approved
                    </span>. 
                    
                    You may claim your item on {{ Carbon\Carbon::parse($reservation->timein)->toFormattedDateString() }} from {{ Carbon\Carbon::parse($reservation->timein)->format('h:i A') }} to {{ Carbon\Carbon::parse($reservation->timeout)->format('h:i A') }}.
                @elseif($reservation->approval == 2)
                    <span class="label label-danger">Disapproved</span> 
                    due to the following reasons:
                @elseif($reservation->approval == 0)
                    still <span class="label label-info">Undecided</span>.
                @endif
            </p>
              
      		@if($reservation->approval == 2)
                <p class="text-muted">
                    <blockquote>
                    <p>{{ $reservation->remark }}</p>
                    <footer>Laboratory Staff</footer>
                    </blockquote>
                </p>
            @endif
              
            <p class="text-muted">
                Reserved Items:
                <ul class="list-unstyled text-muted">
                    @foreach($reservation->itemprofile as $item)
                    <li> - {{ $item->inventory->itemtype->name }} </li>
                    @endforeach
                </ul>
            </p>  
            <p class="text-muted">
                Thank you for your kind consideration!
            </p> 
            
            <p 
                class="text-muted col-md-offset-8 col-md-4 text-left"  
                style="display: block; margin-bottom: 30px;"
            >
                Sincerely Yours,
                <br />
                The LabRMS Team
            </p> 
            
            <p class="text-muted" style="font-size: 12px;">
                Reservation Details:
                Name : {{ $reservation->user->firstname }} {{ $reservation->user->lastname }} <br />
                Date: {{ Carbon\Carbon::parse($reservation->timein)->toFormattedDateString() }} <br />
                Time Start : {{ Carbon\Carbon::parse($reservation->timein)->format('h:m a') }} <br />
                Time End : {{ Carbon\Carbon::parse($reservation->timeout)->format('h:m a') }} <br />
                
                @if($reservation->user->accesslevel == 4)
                    Faculty in-charge : {{ $reservation->facultyincharge }} <br />
                @endif
            </p> 
		</div>
	</div>
</div>
@stop
