<div class="form-group">
    <label for="room">Room</label>
    <select 
        id='items'
        name="room"
        class='form-control multi-select'
        data-live-search=true 
        >

        @forelse($rooms as $room)
            <option
                value="{{ $room->id }}"
                {{ $room->id == old('room') || $room->id == $workstation->room_id ? 'selected' : ''  }}
                >{{ $room->name }}</option>
        @empty
            
        @endforelse
    </select> 
</div>