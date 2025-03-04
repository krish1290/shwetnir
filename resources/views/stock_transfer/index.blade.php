@extends('layouts.app')
@section('title', __('lang_v1.stock_transfers'))

@section('content')

    <!-- Content Header (Page header) -->
    <div class="sub-header-container  no-print">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('lang_v1.stock_transfers')</a></li>
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
                            <!-- All Sales comission agent -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <section class="content no-print">
                                    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_stock_transfers')])
                                        <div class="table-header">

                                            <div class="box-tools">
                                                <a class="btn btn-block btn-primary"
                                                    href="{{ action([\App\Http\Controllers\StockTransferController::class, 'create']) }}">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped ajax_view"
                                                id="stock_transfer_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('messages.date')</th>
                                                        <th>@lang('purchase.ref_no')</th>
                                                        <th>@lang('lang_v1.location_from')</th>
                                                        <th>@lang('lang_v1.location_to')</th>
                                                        <th>@lang('sale.status')</th>
                                                        <th>@lang('lang_v1.shipping_charges')</th>
                                                        <th>@lang('stock_adjustment.total_amount')</th>
                                                        <th>@lang('purchase.additional_notes')</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    @endcomponent
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('stock_transfer.partials.update_status_modal')

    <section id="receipt_section" class="print_section"></section>

    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/stock_transfer.js?v=' . $asset_v) }}"></script>
@endsection
