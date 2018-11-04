

<div class="form-group">
    <label for="os">Operating System License Key</label>
    <input
        type="text"
        class="form-control"
        placeholder="License Key"
        {{ isset($workstation->oskey) ? $workstation->oskey : old('os') }} />
</div>

<div class="form-group">
    <label for="systemunit">System Unit</label>
    <select 
        id='items'
        name="system_unit"
        class='form-control multi-select'
        data-live-search=true 
        >

        @if(isset($workstation->systemunit))
            <option
                value="{{ $workstation->systemunit_id }}"
                selected
            >
                {{ $workstation->systemunit->local_id }}
            </option>
        @endif

        @forelse($systemunits as $item)
            <option
                value="{{ $item->id }}"
                {{ $item->id != old('system_unit') ? '' : 'selected'  }}
                >
                {{ $item->local_id  }}
            </option>
        @empty
            
        @endforelse
    </select> 
</div>

<div class="form-group">
    <label for="monitor">Monitor</label>
    <select 
        id='items'
        name="monitor"
        class='form-control multi-select'
        data-live-search=true
        >

        @if(isset($workstation->monitor))
            <option
                value="{{ $workstation->monitor_id }}"
                selected
            >
                {{ $workstation->monitor->local_id }}
            </option>
        @endif

        @forelse($monitors as $item)
            <option
                value="{{ $item->id }}"
                {{ $item->id != old('monitor') ? '' : 'selected'  }}
                >
                {{ $item->local_id  }}
            </option>
        @empty
            
        @endforelse
    </select> 
</div>

<div class="form-group">
    <label for="avr">AVR</label>
    <select 
        id='items'
        name="avr"
        class='form-control multi-select'
        data-live-search=true
        >

        @if(isset($workstation->avr))
            <option
                value="{{ $workstation->avr_id }}"
                selected
            >
                {{ $workstation->avr->local_id }}
            </option>
        @endif

        @forelse($avrs as $item)
            <option
                value="{{ $item->id }}"
                {{ $item->id != old('avr') ? '' : 'selected'  }}
                >
                {{ $item->local_id  }}
            </option>
        @empty
        
        @endforelse
    </select> 
</div>

<div class="form-group">
    <label for="keyboard">Keyboard</label>
    <select 
        id='items'
        name="keyboard"
        class='form-control multi-select'
        data-live-search=true
        >

        @if(isset($workstation->keyboard))
            <option
                value="{{ $workstation->keyboard_id }}"
                selected
            >
                {{ $workstation->keyboard->local_id }}
            </option>
        @endif

        @forelse($keyboards as $item)
            <option
                value="{{ $item->id }}"
                {{ $item->id != old('keyboard') ? '' : 'selected'  }}
                >
                {{ $item->local_id  }}
            </option>
        @empty
        
        @endforelse
    </select> 
</div>

<div class="form-group">
    <label for="mouse">Mouse</label>
    <input 
        type="checkbox"
        id="mouse"
        name="mouse"
        {{ old('mouse') == true || isset($workstation->mouse) }} />
</div>

<div class="form-group">
    <button 
        class="btn btn-primary btn-lg btn-block" 
        name="create" 
        type="submit">
        <span class="glyphicon glyphicon-check"></span> Add
    </button>
</div>