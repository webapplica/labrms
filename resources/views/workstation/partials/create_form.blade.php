

<div class="form-group">
    {{ Form::label('os','OS License Key') }}

    {{ Form::text('os', isset($workstation->oskey) ? $workstation->oskey : old('os'), [
        'id' => 'os',
        'class' => 'form-control',
        'placeholder' => 'OS License Key'
    ]) }}
</div>

<div class="form-group">
    {{ Form::label('systemunit','System Unit') }}
    <select 
        id='items'
        name="system_unit"
        class='form-control multi-select'
        data-live-search=true
        >
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
    {{ Form::label('monitor', 'Monitor') }}
    <select 
        id='items'
        name="monitor"
        class='form-control multi-select'
        data-live-search=true
        >
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
    {{ Form::label('avr', 'AVR') }}
    <select 
        id='items'
        name="avr"
        class='form-control multi-select'
        data-live-search=true
        >
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
    {{ Form::label('keyboard','Keyboard') }}
    <select 
        id='items'
        name="keyboard"
        class='form-control multi-select'
        data-live-search=true
        >
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
    {{ Form::label('mouse', 'Mouse: ') }}
    {{ Form::checkbox('mouse', old('mouse'), [
        'id' => 'mouse',
    ]) }}
</div>

<div class="form-group">
    <button 
        class="btn btn-primary btn-lg btn-block" 
        name="create" 
        type="submit">
        <span class="glyphicon glyphicon-check"></span> Add
    </button>
</div>