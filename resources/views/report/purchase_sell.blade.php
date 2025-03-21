@extends('layouts.app')
@section('title', __('report.purchase_sell'))

@section('content')

    <!-- Content Header (Page header) -->
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
                                <li class="breadcrumb-item"><a
                                        href="{{ url('report.purchase_sell') }}">@lang('report.purchase_sell')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('report.purchase_sell_msg')</span></li>
                            </ol>

                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>


    <!-- Main content -->
    <section class="content">

        <div class="print_section row m-0">
            <div class="col-xs-12">
                <h3>{{ session()->get('business.name') }} - @lang('report.purchase_sell')</h3>
            </div>
        </div>
        <div class="row no-print m-0">
            <div class="col-md-3 col-md-offset-7 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                    <select class="form-control select2" id="purchase_sell_location_filter">
                        @foreach ($business_locations as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <div class="form-group pull-right">
                    <div class="input-group">
                        <button type="button" class="btn btn-primary" id="purchase_sell_date_filter">
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
            <div class="col-xs-6">
                @component('components.widget', ['title' => __('purchase.purchases')])
                    <table class="table table-striped">
                        <tr>
                            <th>{{ __('report.total_purchase') }}:</th>
                            <td>
                                <span class="total_purchase">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('report.purchase_inc_tax') }}:</th>
                            <td>
                                <span class="purchase_inc_tax">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('lang_v1.total_purchase_return_inc_tax') }}:</th>
                            <td>
                                <span class="purchase_return_inc_tax">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('report.purchase_due') }}: @show_tooltip(__('tooltip.purchase_due'))</th>
                            <td>
                                <span class="purchase_due">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                    </table>
                @endcomponent
            </div>

            <div class="col-xs-6">
                @component('components.widget', ['title' => __('sale.sells')])
                    <table class="table table-striped">
                        <tr>
                            <th>{{ __('report.total_sell') }}:</th>
                            <td>
                                <span class="total_sell">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('report.sell_inc_tax') }}:</th>
                            <td>
                                <span class="sell_inc_tax">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('lang_v1.total_sell_return_inc_tax') }}:</th>
                            <td>
                                <span class="total_sell_return">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('report.sell_due') }}: @show_tooltip(__('tooltip.sell_due'))</th>
                            <td>
                                <span class="sell_due">
                                    <i class="fas fa-sync fa-spin fa-fw"></i>
                                </span>
                            </td>
                        </tr>
                    </table>
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                @component('components.widget')
                    @slot('title')
                        {{ __('lang_v1.overall') }}
                        ((@lang('business.sale') - @lang('lang_v1.sell_return')) - (@lang('lang_v1.purchase') - @lang('lang_v1.purchase_return')) )
                        @show_tooltip(__('tooltip.over_all_sell_purchase'))
                    @endslot
                    <h3 class="text-muted">
                        {{ __('report.sell_minus_purchase') }}:
                        <span class="sell_minus_purchase">
                            <i class="fas fa-sync fa-spin fa-fw"></i>
                        </span>
                    </h3>

                    <h3 class="text-muted">
                        {{ __('report.difference_due') }}:
                        <span class="difference_due">
                            <i class="fas fa-sync fa-spin fa-fw"></i>
                        </span>
                    </h3>
                @endcomponent
            </div>
        </div>
        <div class="row no-print m-0">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary pull-right" aria-label="Print" onclick="window.print();"><i
                        class="fa fa-print"></i> @lang('messages.print')</button>
            </div>
        </div>


    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

@endsection
