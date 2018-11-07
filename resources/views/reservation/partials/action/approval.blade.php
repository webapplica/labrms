
@if($reservation->approval == 0)
    <span class="pull-right">
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