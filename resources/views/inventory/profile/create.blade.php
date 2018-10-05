@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-offset-3 col-md-6 panel panel-body">

	<legend>
		<h3 class="text-muted">Item Profiling: {{ $inventory->brand . ' ' . $inventory->model }}</h3>
	</legend>

	<ol class="breadcrumb">
		<li><a href="{{ url('inventory') }}">Inventory</a></li>
		<li><a href="{{ url('inventory/' . $inventory->id) }}">{{ $inventory->brand . ' ' . $inventory->model }}</a></li>
		<li class="active">Profile</li>
	</ol>

	@include('errors.alert')

	{{ Form::open([ 'method' => 'post', 'url' => url('inventory/' . $inventory->id . '/profile'), 'id' => 'profile-form' ]) }}
	
		<div class="form-group">
			<p class="text-muted pull-right">
				<span class="label label-primary label-lg">Unprofiled Items: {{ $unprofiled_items_count }}</span>
			</p>
		</div>

		{{-- form for linking items to current table --}}
		@include('inventory.profile.partials.create_form')
		{{-- form for linking items to current table --}}

		{{-- display the table for items --}}
		@include('inventory.profile.partials.item_table')
		{{-- display the table for items --}}
		
		<div class="form-group pull-right">
			{{ Form::button('Add Item', [
				'class' => 'btn btn-md btn-success',
				'name' => 'add-item',
				'id' => 'add-item-btn'
			]) }}

			{{ Form::submit('Profile', [
				'class' => 'btn btn-md btn-primary',
				'name' => 'Profile',
				'id' => 'submit-btn',
			]) }}
		</div>

		<div class="clearfix"></div>
	{{ Form::close() }}
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	(function($) {
		var table = $('#items-table');
		var tbody = $('#items-table > tbody');
		var addItemsButton = $('#add-item-btn');
		var quantityToProfile = $('.profile-quantity');
		
		// append the following html entities on click of
		// add items button
		addItemsButton.on('click', function (callback) {

			// append the following elements on table body
			tbody.append(

				// append a table row with items-table__trow class
				$('<tr />', {'class': 'items-table__trow'}).append(

					// append a table data with input form displaying the
					// property number of the university
					$('<td />').append(
						$('<input />', {
							type: 'text',
							name: 'property-number[]',
							class: 'form-control property-number',
							placeholder: 'Input university property number here',
						})
					),
					
					// append a table data with input form displaying the
					// property number of the local office
					$('<td />').append(
						$('<input />', {
							type: 'text',
							name: 'local-property-number[]',
							class: 'form-control local-property-number',
							placeholder: 'Input local property number here',
						})
					),
					
					// append a table data with input form displaying the
					// serial id of the university
					$('<td />').append(
						$('<input />', {
							type: 'text',
							name: 'serial-id[]',
							class: 'form-control serial-id',
							placeholder: 'Input serial id here',
						})
					),
					
					// append a table data with the remove button
					$('<td />').append(
						$('<button />', {
							type: 'button',
							name: '[]',
							class: 'remove-btn btn btn-sm btn-danger',
							text: ' Remove',
						}).prepend( $('<span />', { class: 'glyphicon glyphicon-remove'}))
					)
				)
			);

			// check if the table has elements on load
			// if the table has elements, removes the 'no data specified'
			// text if it exists. If the table has no elements, shows the
			// 'no data specified' text
			checkTableLength();
		});

		// on button click of remove button, triggers functions to display
		// or hide no data specified text
		table.on('click', '.remove-btn', function(callback) {
			$(this).closest('tr').remove();
			checkTableLength();
		});

		// check if the table has elements on load
		// if the table has elements, removes the 'no data specified'
		// text if it exists. If the table has no elements, shows the
		// 'no data specified' text
		$(document).ready(function() {
			checkTableLength();
		});

		// function to show or remove the 'no data specified' row on the table
		var checkTableLength = function () {
			var childElementsLength = tbody.children().length;

			// shows the 'no data specified' row on the table
			// if the child element is less that zero 
			if(childElementsLength <= 0) {
				tbody.append(
					$('<tr />', {'class': 'items-table__trow no-data-specified', rowspan: 2}).append(
						$('<td />', { colspan: 4, text: 'No data specified', class: 'text-center' })
					),
				);
			} 
			
			// removes the 'no data specified' row on the table
			// if the child element is greater that zero 
			// sets the value of child element of the table
			else {
				$('.no-data-specified').remove();
				childElementsLength = tbody.children().length;
			}
			
			quantityToProfile.text(childElementsLength);
		}

	}(jQuery));
</script>
@stop
