
@if($reservation->approval == 0)
    <span class="pull-right">
        <a 
            href="{{ url('reservation/' . $reservation->id . '/cancel') }}" 
            id="cancel" 
            class="btn btn-sm btn-default">
            {{ _('Cancel') }}
        </a>

        <button 
            data-id="`{{ $reservation->id }}`" 
            id="approve" 
            class="btn btn-sm btn-success">
            {{ _('Approve') }}
        </button>

        <button 
            id="disapprove" 
            data-id="`{{ $reservation->id }}`" 
            data-reason="`{{ $reservation->remark }}"  
            class="btn btn-sm btn-danger">
            {{ _('Disapprove') }}
        </button>
    </span>
@endif