<div class="form-group">
	<div class="col-sm-3">
		{{ Form::label('faculty', 'Faculty-in-charge') }}
	</div>

	<div class="col-sm-9">
		<select 
			name="faculty" 
			id="faculty" 
			class="form-control">
			@forelse($personnels as $person)
				<option 
					value="{{ $person->id }}" 
					{{ $person->id == old('faculty') ? 'selected' : "" }}>
					{{ $person->full_name }}
				</option>
			@empty
				<option>
					{{ __('No options available') }}
				</option>
			@endforelse
		</select>	
	</div>
</div>

<div class="form-group">
	<div class="col-sm-3">
		{{ Form::label('date','Date of Use', [
			'data-language'=>"en"
		]) }}
	</div>
	<div class="col-sm-9">
		{{ Form::text('date', old('date'), [
			'id' => 'date',
			'class'=>'form-control',
			'placeholder'=>'Month Day, Year',
			'readonly',
			'style'=>'background-color: #ffffff;'
		]) }}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-3">
		{{ Form::label('startTime','Time Start') }}
	</div>
	<div class="col-sm-9">
		<div class="input-group startTime">
			<input 
				type="text" 
				class="form-control"
				name="time_start"
				id="startTime"
				value="{{ old('startTime') ?: $defaultStartTime }}" 
				readonly 
				style="background-color: white;" />
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-time"></span>
			</span>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-3">
 		{{ Form::label('returnTime','Time End') }}
	</div>
	<div class="col-sm-9">
		<div class="input-group returnTime">
				<input 
					type="text" 
					class="form-control"
					name="return_time"
					id="returnTime"
					value="{{ old('returnTime') ?: $defaultReturnTime }}"
					readonly 
					style="background-color: white;" />
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-time"></span>
			</span>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-xs-3">
		{{ Form::label('items', 'Items') }}
	</div>
	<div class="col-xs-9"> 
		<select 
			id='items'
			class='form-control multi-select'
			name="items[]"
			multiple
			>
			@forelse($items as $item)
				<option
					value="{{ $item->id }}"
					{{ old('items') && in_array($item->id, old('items')) ? 'selected' : '' }}
					>
					{{ $item->descriptive_name }}
				</option>
			@empty
				<option>Empty</option>
			@endforelse
		</select> 
	</div>
</div>

<div class="form-group">
	<div class="col-sm-3">
	 {{ Form::label('location', 'Location') }}
	</div>
	<div class="col-sm-9">
		{{ Form::select('location', $rooms, old('location'),[
			'id' => 'location',
			'class' => 'form-control'
		]) }}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-3">
		{{ Form::label('purpose', 'Purpose') }}
	</div>
	<div class="col-sm-9">

		{{ Form::select('purpose', $purposes, old('purpose'), [
			'id' => 'purpose-select',
			'class' => 'form-control'
		]) }}

		<div class="checkbox">
			<label>
				<input 
					type="checkbox" 
					name="not_in_list" 
					id="not-in-list-checkbox"> Not in the list?
			</label>
		</div>

		{{ Form::textarea('alternative_explanation', old('alternative_explanation'), [
			'id' => 'alternative_explanation',
			'class' => 'form-control',
			'placeholder' => 'Enter details here...',
			'style' => 'display: none;'
		]) }}

	</div>
</div>

<div class="form-group">
	<div class="col-sm-12">
		<p class="text-muted text-justified">
			{{ __('reservation.terms_and_condition_label') }}. <span class="text-danger"> {{ __('label.no_longer_editable') }}</span>
		</p>
	</div>
</div>