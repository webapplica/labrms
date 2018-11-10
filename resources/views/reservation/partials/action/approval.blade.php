
@if($reservation->approval == 0)
    <span class="pull-right">
        <a 
            href="{{ url('reservation/' . $reservation->id . '/cancel') }}" 
            id="cancel" 
            class="btn btn-sm btn-default">
            {{ _('Cancel') }}
        </a>
        
        <a 
            href="{{ url('reservation/' . $reservation->id . '/approve') }}" 
            id="approve" 
            class="btn btn-sm btn-success">
            {{ _('Approve') }}
        </a>

        <a 
            href="{{ url('reservation/' . $reservation->id . '/disapprove') }}" 
            id="disapprove" 
            class="btn btn-sm btn-danger">
            {{ _('Disapprove') }}
        </a>
    </span>
@endif