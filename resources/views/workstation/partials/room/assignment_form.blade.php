<div class="form-group">
    <label for="room">Room</label>
    <select 
        id='items'
        name="room"
        class='form-control multi-select'
        data-live-search=true 
        >

        @if(isset($workstation->room_id))
            <option
                value="{{ $workstation->room_id }}"
                selected
            >
                {{ $workstation->room_id }}
            </option>
        @endif

        @forelse($rooms as $room)
            <option
                value="{{ $room->id }}"
                {{ $room->id != old('room') ? '' : 'selected'  }}
                >{{ $room->name  }}</option>
        @empty
            
        @endforelse
    </select> 
</div>