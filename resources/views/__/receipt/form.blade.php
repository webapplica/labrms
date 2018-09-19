{{ csrf_field() }}
<div class="clearfix">
	<!-- item name -->
	<div class="col-sm-12">
		<div class="form-group">
		{{ Form::label('number','Property Acknowledgement Receipt') }}
		{{ Form::text('number',isset($receipt->number) ? $receipt->number : old('number'),[
			'class' => 'form-control',
			'placeholder' => 'Receipt Number',
			'id' => 'number'
		]) }}
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
		{{ Form::label('purchaseorder_number','Purchase Order Number') }}
		{{ Form::text('purchaseorder_number',isset($receipt->purchaseorder_number) ? $receipt->purchaseorder_number : old('purchaseorder_number'),[
			'class' => 'form-control',
			'placeholder' => 'P.O. Number',
			'id' => 'purchaseorder_number'
		]) }}
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
		{{ Form::label('purchaseorder_date','Purchase Order Date') }}
		{{ Form::text('purchaseorder_date',isset($receipt->purchaseorder_date) ? $receipt->purchaseorder_date : old('purchaseorder_date'),[
			'class' => 'form-control',
			'placeholder' => 'P.O. Date',
			'id' => 'purchaseorder_date',
			'readonly',
			'role' => 'button'
		]) }}
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
		{{ Form::label('invoice_number','Invoice Number') }}
		{{ Form::text('invoice_number',isset($receipt->invoice_number) ? $receipt->invoice_number : old('invoice_number'),[
			'class' => 'form-control',
			'placeholder' => 'Invoice Number',
			'id' => 'invoice_number'
		]) }}
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
		{{ Form::label('invoice_date','Invoice Date') }}
		{{ Form::text('invoice_date',isset($receipt->invoice_date) ? $receipt->invoice_date : old('invoice_date'),[
			'class' => 'form-control',
			'placeholder' => 'Invoice Date',
			'id' => 'invoice_date',
			'readonly',
			'role' => 'button'
		]) }}
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
		{{ Form::label('fund_code','Fund Code') }}
		{{ Form::text('fund_code',isset($receipt->fund_code) ? $receipt->fund_code : old('fund_code'),[
			'class' => 'form-control',
			'placeholder' => 'Fund Code',
			'id' => 'fund_code'
		]) }}
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$( "#purchaseorder_date" ).datepicker({
			  changeMonth: true,
			  changeYear: false,
			  maxAge: 59,
			  minAge: 15,
		});

		$( "#invoice_date" ).datepicker({
			  changeMonth: true,
			  changeYear: false,
			  maxAge: 59,
			  minAge: 15,
		});

		@if(old('purchaseorder_date'))
			$('#purchaseorder_date').val('{{ old('purchaseorder_date') }}');
		@else
			$('#purchaseorder_date').val("{{ Carbon\Carbon::now()->toFormattedDateString() }}");
		@endif

		$('#purchaseorder_date').on('change',function(){
			setDate("#purchaseorder_date");
		});

		@if(old('invoice_date'))
			$('#invoice_date').val('{{ old('invoice_date') }}');
		@else
			$('#invoice_date').val("{{ Carbon\Carbon::now()->toFormattedDateString() }}");
		@endif

		$('#invoice_date').on('change',function(){
			setDate("#invoice_date");
		});

		$('#purchaseorder_date').on('change', function(){
			setDate("#purchaseorder_date");
		})

		function setDate(object){
			var object_val = $(object).val()
			var date = moment(object_val).format('MMM DD, YYYY');
			$(object).val(date);
		}
	})
</script>