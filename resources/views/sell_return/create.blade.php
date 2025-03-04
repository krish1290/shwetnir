@extends('layouts.app')
@section('title', __('lang_v1.sell_return'))

@section('content')

    <!-- Content Header (Page header) -->

    <div class="sub-header-container  no-print"style="margin-bottom:40px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('sell_return') }}">@lang('lang_v1.sell_return')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span>{{ _('Add Sell Return') }}</span>
                                </li>
                            </ol>

                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <section class="content no-print">

        @include('layouts.partials.error')

        @if (count($business_locations) == 1)
            @php
                $default_location = current(array_keys($business_locations->toArray()));
            @endphp
        @else
            @php $default_location = null; @endphp
        @endif
        @php
            $default_contact = null;
            $default_reference = null;
            $default_reference_array = [];
            $contact_array = [];
        @endphp
        @if (!empty($transaction))
            @php
                $default_location = $transaction->location_id;
                $default_contact = $transaction->contact_id;
                $contact_array = [$default_contact => $transaction->contact->name]; 
                $default_reference = $transaction->id;
                $default_reference_array = [$default_reference => $transaction->invoice_no];
            @endphp
        @endif
        <div class="row" style="padding:20px">
            <div class="col-sm-3">
                <div class="form-group">
                    {!! Form::label('location_id', __('purchase.business_location') . ':*') !!}
                    {!! Form::select('location_id', $business_locations, $default_location, [
                        'class' => 'form-control',
                        'placeholder' => __('messages.please_select'),
                        'required',
                        'id' => 'select_location_id',
                    ]) !!}
                </div>
            </div>
        </div>
        <input type="hidden" id="product_row_count" value="0">

        {!! Form::open([
            'url' => action([\App\Http\Controllers\SellReturnController::class, 'savereturn']),
            'method' => 'post',
            'id' => 'create_sell_return_form',
        ]) !!}

        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    {!! Form::hidden('location_id', $default_location, ['id' => 'location_id']) !!}

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('contact_id', __('contact.customer') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select('contact_id', $contact_array, $default_contact, [
                                    'class' => 'form-control',
                                    'id' => 'customer_id',
                                    'placeholder' => 'Enter Customer name / phone',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('sell_reference', __('Sell Invoice') . ':*') !!}
                            {!! Form::select('sell_reference', $default_reference_array, $default_reference, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'required',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('invoice_no', __('purchase.ref_no') . ':') !!}
                            {!! Form::text('invoice_no', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('transaction_date', __('purchase.purchase_date') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::text('transaction_date', @format_date('now'), ['class' => 'form-control', 'readonly', 'required']) !!}
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div> <!--box end-->

        <div class="box box-solid"><!--box start-->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                {!! Form::text('search_product', null, [
                                    'class' => 'form-control',
                                    'id' => 'search_product_for_sell_return',
                                    'placeholder' => __('lang_v1.search_product_placeholder'),
                                    'disabled',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $hide_tax = '';
                    if (session()->get('business.enable_inline_tax') == 0) {
                        $hide_tax = 'hide';
                    }
                @endphp

                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" id="product_row_index" value="0">
                        <input type="hidden" id="total_amount" name="final_total" value="0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed"
                                id="sell_return_product_table">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            @lang('sale.product')
                                        </th>
                                        @if (session('business.enable_lot_number'))
                                            <th>
                                                @lang('lang_v1.lot_number')
                                            </th>
                                        @endif
                                        @if (session('business.enable_product_expiry'))
                                            <th>
                                                @lang('product.exp_date')
                                            </th>
                                        @endif
                                        <th class="text-center">
                                            @lang('sale.qty')
                                        </th>
                                        <th class="text-center">
                                            @lang('sale.unit_price')
                                        </th>
                                        <th class="text-center">
                                            @lang('sale.subtotal')
                                        </th>
                                        <th class="text-center"><i class="fa fa-trash" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('tax_id', __('purchase.purchase_tax') . ':') !!}
                            <select name="tax_id" id="tax_id" class="form-control select2"
                                placeholder="'Please Select'">
                                <option value="" data-tax_amount="0" data-tax_type="fixed" selected>@lang('lang_v1.none')
                                </option>
                                @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}" data-tax_amount="{{ $tax->amount }}"
                                        data-tax_type="{{ $tax->calculation_type }}">{{ $tax->name }}</option>
                                @endforeach
                            </select>
                            {!! Form::hidden('tax_amount', 0, ['id' => 'tax_amount']) !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="pull-right"><b>@lang('stock_adjustment.total_amount'):</b> <span id="total_return">0.00</span></div>
                    </div>
                </div>
            </div>
        </div><!--box end-->
        <div class="box box-solid"><!--box start-->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <tr>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('discount_type', __('purchase.discount_type') . ':') !!}
                                        {!! Form::select(
                                            'discount_type',
                                            ['' => __('lang_v1.none'), 'fixed' => __('lang_v1.fixed'), 'percentage' => __('lang_v1.percentage')],
                                            '',
                                            ['class' => 'form-control'],
                                        ) !!}
                                    </div>
                                </td>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::label('discount_amount', __('purchase.discount_amount') . ':') !!}
                                        {!! Form::text('discount_amount', 0, ['class' => 'form-control input_number', 'required']) !!}
                                    </div>
                                </td>
                                <td class="col-md-3">
                                    &nbsp;
                                </td>
                                <td class="col-md-3">
                                    <b>@lang('purchase.discount'):</b>(-)
                                    <span id="total_discount" class="display_currency">0</span>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    {!! Form::hidden('final_total', 0, ['id' => 'final_total_input']) !!}
                                    <b>@lang('lang_v1.total_credit_amt'): </b><span id="total_payable" class="display_currency"
                                        data-currency_symbol='true'>0</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        {!! Form::label('additional_notes', __('Reason of return')) . ':*' !!}
                                        {!! Form::textarea('additional_notes', null, [
                                            'class' => 'form-control',
                                            'id' => 'additional_note',
                                            'rows' => 3,
                                        ]) !!}
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" id="submit_create_sell_return_form"
                            class="btn btn-primary pull-right btn-flat">@lang('messages.save')</button>
                    </div>
                </div>

            </div><!--box end-->
            {!! Form::close() !!}
    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <script src="{{ asset('js/sell_return.js?v=' . $asset_v) }}"></script>
@endsection
