<span 
    style="display: block; font-size: 13px;">{{ __('reservation.items_list_note') }}</span>
<ul class="text-muted">
    @foreach($reservation->item as $item)
    <li>{{ $item->inventory->descriptive_name .  '  ---  ' . $item->local_id }}</li>
    @endforeach
</ul>