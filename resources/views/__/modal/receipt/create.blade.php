<div class="modal fade" id="createReceiptModal" tabindex="-1" role="dialog" aria-labelledby="createReceiptModal">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<legend><h3 class="text-muted">Receipt</h3></legend>
				<form method="post" action="{{ url('receipt') }}" class="form-horizontal">
					@include('receipt.form')
					<div class="form-group pull-right">
						<div class="col-sm-12">
							<button type="submit" id="create" class="btn btn-primary btn-md">
								Create
							</button>
							<button type="type"data-dismiss="modal" aria-label="Close" id="create" class="btn btn-default btn-md">
								Cancel
							</button>
						</div>
					</div>
					<div class=" clearfix"></div>
				</form>
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>
