<div class="modal fade" id="createInventoryModal" tabindex="-1" role="dialog" aria-labelledby="createInventoryModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				{{ Form::open(['method'=>'post','route'=>'inventory.store','class'=>'form-horizontal','id'=>'inventoryForm']) }}
				@include('inventory.item.form')
				{{ Form::close() }}
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>