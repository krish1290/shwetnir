@extends('layouts.app')
@section('title', __('tax_rate.tax_rates'))

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
                                <li class="breadcrumb-item"><a href="{{ url('tax_rate.tax_rates') }}">@lang('tax_rate.tax_rates')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('tax_rate.manage_your_tax_rates')</span></li>
                            </ol>

                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <div class="layout-px-spacing  no-print">
        <div class="layout-top-spacing mb-2">
            <div class="col-md-12 p-0">
                <div class="row">
                    <div class="container p-0 m-0">
                        <div class="row layout-top-spacing date-table-container">
                            <!-- All Products -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <section class="content">
                                    @component('components.widget', ['class' => 'box-primary', 'title' => __('tax_rate.all_your_tax_rates')])
                                        @can('tax_rate.create')
                                            <div class="table-header">
                                                <div class="box-tools">
                                                    <button type="button" class="btn btn-block btn-primary btn-modal"
                                                        data-href="{{ action([\App\Http\Controllers\TaxRateController::class, 'create']) }}"
                                                        data-container=".tax_rate_modal">
                                                        <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                                </div>
                                            </div>
                                        @endcan
                                        @can('tax_rate.view')
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="tax_rates_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('tax_rate.name')</th>
                                                            <th>@lang('tax_rate.rate')</th>
                                                            <th>@lang('messages.action')</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        @endcan
                                    @endcomponent

                                    @component('components.widget', ['class' => 'box-primary'])
                                        @slot('title')
                                            @lang('tax_rate.tax_groups') ( @lang('lang_v1.combination_of_taxes') ) @show_tooltip(__('tooltip.tax_groups'))
                                        @endslot

                                        @can('tax_rate.create')
                                            <div class="table-header">
                                                <div class="box-tools">
                                                    <button type="button" class="btn btn-block btn-primary btn-modal"
                                                        data-href="{{ action([\App\Http\Controllers\GroupTaxController::class, 'create']) }}"
                                                        data-container=".tax_group_modal">
                                                        <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                                </div>
                                            </div>
                                        @endcan
                                        @can('tax_rate.view')
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="tax_groups_table">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('tax_rate.name')</th>
                                                            <th>@lang('tax_rate.rate')</th>
                                                            <th>@lang('tax_rate.sub_taxes')</th>
                                                            <th>@lang('messages.action')</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        @endcan
                                    @endcomponent

                                    <div class="modal fade tax_rate_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
                                    </div>
                                    <div class="modal fade tax_group_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
                                    </div>

                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content -->
@endsection
