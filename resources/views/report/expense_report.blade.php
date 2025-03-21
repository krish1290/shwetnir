@extends('layouts.app')
@section('title', __('report.expense_report'))

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('report.expense_report')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row no-print">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    {!! Form::open([
                        'url' => action([\App\Http\Controllers\ReportController::class, 'getExpenseReport']),
                        'method' => 'get',
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
                                'placeholder' => __('report.all'),
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
                            {!! Form::label('trending_product_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'trending_product_date_range',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right">@lang('report.apply_filters')</button>
                    </div>
                    {!! Form::close() !!}
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @component('components.widget', ['class' => 'box-primary'])
                    {!! $chart->container() !!}
                @endcomponent
            </div>
        </div>
        <div class="row date-table-container">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary'])
                    <table class="table" id="expense_report_table">
                        <thead>
                            <tr>
                                <th>@lang('expense.expense_categories')</th>
                                <th>@lang('report.total_expense')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_expense = 0;
                            @endphp
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ $expense['category'] ?? __('report.others') }}</td>
                                    <td><span class="display_currency"
                                            data-currency_symbol="true">{{ $expense['total_expense'] }}</span></td>
                                </tr>
                                @php
                                    $total_expense += $expense['total_expense'];
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>@lang('sale.total')</td>
                                <td><span class="display_currency" data-currency_symbol="true">{{ $total_expense }}</span></td>
                            </tr>
                        </tfoot>
                    </table>
                @endcomponent
            </div>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    {!! $chart->script() !!}
@endsection
