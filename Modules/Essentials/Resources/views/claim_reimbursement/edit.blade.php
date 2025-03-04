<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action(
                [\Modules\Essentials\Http\Controllers\ClaimReimbursementController::class, 'update'],
                $allowance->id,
            ),
            'method' => 'put',
            'id' => 'add_allowance_form',
            'files' => true,
        ]) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('essentials::lang.edit_claim_reimbursement')</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('description', __('lang_v1.description') . ':*') !!}
                    {!! Form::text('description', $allowance->description, [
                        'class' => 'form-control',
                        'required',
                        'placeholder' => __('lang_v1.description'),
                    ]) !!}
                </div>

                <div class="form-group col-md-12">
                    {{-- {!! Form::label('type', __('lang_v1.type') . ':*') !!}
                    {!! Form::select(
                        'type',
                        ['allowance' => __('essentials::lang.allowance'), 'deduction' => __('essentials::lang.deduction')],
                        $allowance->type,
                        ['class' => 'form-control', 'required'],
                    ) !!} --}}
                    {!! Form::hidden('type', 'allowance', ['id' => 'type']) !!}
                </div>
                @if (auth()->user()->can('essentials.approve_claim_reimbursement') ||
                        auth()->user()->can('superadmin'))
                    <div class="form-group col-md-12">
                        {!! Form::label('employees', __('essentials::lang.employee') . ':') !!}
                        {!! Form::select('employees[]', $users, $selected_users, ['class' => 'form-control select2', 'multipler']) !!}
                    </div>
                @else
                    {!! Form::hidden('employees[]', $employes, ['id' => 'employees']) !!}
                @endif


                <div class="form-group col-md-6">
                    {{-- {!! Form::label('amount_type', __('essentials::lang.amount_type') . ':*') !!}
                    {!! Form::select(
                        'amount_type',
                        ['fixed' => __('lang_v1.fixed'), 'percent' => __('lang_v1.percentage')],
                        $allowance->amount_type,
                        ['class' => 'form-control', 'required'],
                    ) !!} --}}
                    {!! Form::hidden('amount_type', 'fixed', ['id' => 'type']) !!}
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('amount', __('sale.amount') . ':*') !!}
                    {!! Form::text('amount', @num_format($allowance->amount), [
                        'class' => 'form-control input_number',
                        'placeholder' => __('sale.amount'),
                        'required',
                    ]) !!}
                </div>

                <div class="form-group col-md-12">
                    {{-- @show_tooltip(__('essentials::lang.applicable_date_help')) --}}
                    {!! Form::label('applicable_date', __('essentials::lang.date') . ':') !!}
                    <div class="input-group data">
                        {!! Form::text('applicable_date', $applicable_date, [
                            'class' => 'form-control',
                            'placeholder' => __('essentials::lang.date'),
                            'readonly',
                        ]) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('document', __('purchase.attach_document') . ':') !!}
                        {!! Form::file('document', [
                            'id' => 'upload_document',
                            'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types'))),
                        ]) !!}
                        <small>
                            <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                                @includeIf('components.document_help_text')</p>
                        </small>
                    </div>
                </div>
                @if (auth()->user()->can('essentials.approve_claim_reimbursement') && $logged_in_user_is_manager ||
                        auth()->user()->can('superadmin') && $logged_in_user_is_manager)
                    <div class="form-group col-md-12">
                        {!! Form::label('is_approved', __('Approve') . ':*') !!}
                        {!! Form::select('is_approved', [null => 'Select', '1' => __('Yes'), '0' => __('No')], $allowance->is_approved, [
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                @endif
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
