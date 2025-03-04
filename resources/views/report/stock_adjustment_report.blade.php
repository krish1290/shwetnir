@extends('layouts.app')
@section('title', __('report.stock_adjustment_report'))

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('report.stock_adjustment_report')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>


    <!-- Main content -->
    <section class="content">
        <div class="row no-print m-0">
            <div class="col-md-3 col-md-offset-7 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                    <select class="form-control select2" id="stock_adjustment_location_filter">
                        @foreach ($business_locations as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <div class="form-group pull-right">
                    <div class="input-group">
                        <button type="button" class="btn btn-primary" id="stock_adjustment_date_filter">
                            <span>
                                <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-6">
                @component('components.widget')
                    <table class="table no-border">
                        <tr>
                            <th>{{ __('report.total_normal') }}:</th>
                            <td>
                                <span class="total_normal">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('report.total_abnormal') }}:</th>
                            <td>
                                <span class="total_abnormal">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('report.total_stock_adjustment') }}:</th>
                            <td>
                                <span class="total_amount">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                    </table>
                @endcomponent
            </div>

            <div class="col-sm-6">
                @component('components.widget')
                    <table class="table no-border">
                        <tr>
                            <th>{{ __('report.total_recovered') }}:</th>
                            <td>
                                <span class="total_recovered">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                @endcomponent
            </div>
        </div>

        <div class="row date-table-container">
            <div class="col-sm-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('stock_adjustment.stock_adjustments')])
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="stock_adjustment_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('business.location')</th>
                                    <th>@lang('stock_adjustment.adjustment_type')</th>
                                    <th>@lang('stock_adjustment.total_amount')</th>
                                    <th>@lang('stock_adjustment.total_amount_recovered')</th>
                                    <th>@lang('stock_adjustment.reason_for_stock_adjustment')</th>
                                    <th>@lang('lang_v1.added_by')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>


    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/stock_adjustment.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
