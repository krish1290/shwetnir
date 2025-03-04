@extends('layouts.app')
@section('title', __('report.stock_report'))

@section('content')

    <!-- Content Header (Page header) -->
    <div class="sub-header-container  no-print"style="margin-bottom:30px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('report.stock_report')</a></li>
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
                        'id' => 'stock_report_filter_form',
                    ]) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                            {!! Form::select('location_id', $business_locations, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('category_id', __('category.category') . ':') !!}
                            {!! Form::select('category', $categories, null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id' => 'category_id',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                            {!! Form::select('sub_category', [], null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id' => 'sub_category_id',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('brand', __('product.brand') . ':') !!}
                            {!! Form::select('brand', $brands, null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('unit', __('product.unit') . ':') !!}
                            {!! Form::select('unit', $units, null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    @if ($show_manufacturing_data)
                        <div class="col-md-3">
                            <div class="form-group">
                                <br>
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('only_mfg', 1, false, ['class' => 'input-icheck', 'id' => 'only_mfg_products']) !!} {{ __('manufacturing::lang.only_mfg_products') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    {!! Form::close() !!}
                @endcomponent
            </div>
        </div>
        @can('view_product_stock_value')
            <div class="row">
                <div class="col-md-12">
                    @component('components.widget', ['class' => 'box-solid'])
                        <table class="table no-border">
                            <tr>
                                <td>@lang('report.closing_stock') (@lang('lang_v1.by_purchase_price'))</td>
                                <td>@lang('report.closing_stock') (@lang('lang_v1.by_sale_price'))</td>
                                <td>@lang('lang_v1.potential_profit')</td>
                                <td>@lang('lang_v1.profit_margin')</td>
                            </tr>
                            <tr>
                                <td>
                                    <h3 id="closing_stock_by_pp" class="mb-0 mt-0"></h3>
                                </td>
                                <td>
                                    <h3 id="closing_stock_by_sp" class="mb-0 mt-0"></h3>
                                </td>
                                <td>
                                    <h3 id="potential_profit" class="mb-0 mt-0"></h3>
                                </td>
                                <td>
                                    <h3 id="profit_margin" class="mb-0 mt-0"></h3>
                                </td>
                            </tr>
                        </table>
                    @endcomponent
                </div>
            </div>
        @endcan
        <div class="row date-table-container">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-solid'])
                    @include('report.partials.stock_report_table')
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
