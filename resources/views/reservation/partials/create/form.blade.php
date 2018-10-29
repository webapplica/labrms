<div class="form-group">
	<div class="col-sm-3">
	{{ Form::label('name', 'Faculty-in-charge') }}
	</div>
	<div class="col-sm-9">
		<select 
			name="name" 
			id="name" 
			class="form-control">
			@forelse($personnels as $person)
				<option 
					value="{{ $person->id }}" 
					{{ $person->id == old('name') ? 'selected' : "" }}>
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
		{{ Form::text('startTime', old('startTime'), [
			'class'=>'form-control',
			'placeholder'=>'Hour : Min',
			'id' => 'start-time',
			'readonly',
			'style'=>'background-color: #ffffff;'
		]) }}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-3">
 		{{ Form::label('returnTime','Time End') }}
	</div>
	<div class="col-sm-9">
		{{ Form::text('returnTime', old('returnTime'), [
			'class' => 'form-control background-white',
			'placeholder' => 'Hour : Min',
			'id' => 'returnTime',
			'readonly',
			'style' => 'background-color: #ffffff;'
		]) }}
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
			multiple
			>
			@forelse($items as $item)
				<option
					value="{{ $item->id }}"
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
				<input type="checkbox" name="contains" id="has-purpose-checkbox"> Not in the list?
			</label>
		</div>

		{{ Form::textarea('description', old('description'), [
			'id' => 'description-textarea',
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