@extends('layouts.app')
@section('title', __('lang_v1.product_purchase_report'))

@section('content')

    <!-- Content Header (Page header) -->
    <div class="sub-header-container  no-print"style="margin-bottom-30px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('lang_v1.product_purchase_report')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    {!! Form::open([
                        'url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']),
                        'method' => 'get',
                        'id' => 'product_purchase_report_form',
                    ]) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('search_product', __('lang_v1.search_product') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input type="hidden" value="" id="variation_id">
                                {!! Form::text('search_product', null, [
                                    'class' => 'form-control',
                                    'id' => 'search_product',
                                    'placeholder' => __('lang_v1.search_product_placeholder'),
                                    'autofocus',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('supplier_id', __('purchase.supplier') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select('supplier_id', $suppliers, null, [
                                    'class' => 'form-control select2',
                                    'placeholder' => __('messages.please_select'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-map-marker"></i>
                                </span>
                                {!! Form::select('location_id', $business_locations, null, [
                                    'class' => 'form-control select2',
                                    'placeholder' => __('messages.please_select'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                            {!! Form::label('product_pr_date_filter', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'product_pr_date_filter',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('ppr_brand_id', __('product.brand') . ':') !!}
                            {!! Form::select('ppr_brand_id', $brands, null, [
                                'class' => 'form-control select2',
                                'placeholder' => __('lang_v1.all'),
                            ]) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                @endcomponent
            </div>
        </div>
        <div class="row date-table-container">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary'])
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="product_purchase_report_table">
                            <thead>
                                <tr>
                                    <th>@lang('sale.product')</th>
                                    <th>@lang('product.sku')</th>
                                    <th>@lang('purchase.supplier')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('sale.qty')</th>
                                    <th>@lang('lang_v1.total_unit_adjusted')</th>
                                    <th>@lang('lang_v1.unit_perchase_price')</th>
                                    <th>@lang('sale.tax')</th>
                                    <th>@lang('sale.subtotal')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 footer-total text-center">
                                    <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                    <td id="footer_total_purchase"></td>
                                    <td id="footer_total_adjusted"></td>
                                    <td colspan="2"></td>
                                    <td><span class="display_currency" id="footer_subtotal" data-currency_symbol="true"></span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="modal fade view_register" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
