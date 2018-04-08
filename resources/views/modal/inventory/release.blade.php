<div class="modal fade" id="releaseInventoryModal" tabindex="-1" role="dialog" aria-labelledby="releaseInventoryModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ Form::open(['method'=>'post','url'=>'inventory/release','class'=>'form-horizontal','id'=>'releaseInventoryForm']) }}

					<legend>
						<h3>{{ $inventory->code }}</h3>
					</legend>

					<input type="hidden" name="id" id="inventory-id" />

					@include('errors.alert')

					<div class="form-group">
						<div class="col-sm-12">
							{{ Form::label('Quantity') }}
							{{ Form::number('quantity', old('quantity'), [
								'class' => 'form-control',
								'placeholder' => 'Quantity to Release',
								'required'
							]) }}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							{{ Form::label('Purpose') }}
							{{ Form::textarea('purpose', old('purpose'), [
								'class' => 'form-control',
								'placeholder' => 'Purpose for Releasing',
								'required'
							]) }}
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12">
							<p>By: {{ Auth::user()->fullname }}</p>
						</div>
					</div>

					<button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
				{{ Form::close() }}
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>