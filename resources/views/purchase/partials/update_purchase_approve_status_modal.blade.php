<div class="modal fade" id="update_purchase_approve_status_modal" tabindex="-1" role="dialog"
    	aria-labelledby="gridSystemModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

		{!! Form::open(['url' => action([\App\Http\Controllers\PurchaseController::class, 'updateApproveStatus']), 'method' => 'post', 'id' => 'update_purchase_approve_status_form' ]) !!}

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">@lang( 'lang_v1.update_purchase_approve_status' )</h4>
		</div>

		<div class="modal-body">
			<div class="form-group">
				{!! Form::label('status', __('purchase.purchase_approve_status') . ':*') !!}
				{!! Form::select('approve_status', $orderApproveStatuses, null, ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required']); !!}

				{!! Form::hidden('approve_purchase_id', null, ['id' => 'approve_purchase_id']); !!}
			</div>
		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
		</div>

		{!! Form::close() !!}

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
