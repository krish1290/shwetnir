@extends('layouts.app')
@section('title', __('lang_v1.sales_commission_agents'))
@push('plugin-styles')
    {!! Html::style('plugins/table/datatable/datatables.css') !!}
    {!! Html::style('plugins/table/datatable/dt-global_style.css') !!}
@endpush
@section('content')


    <!--  Navbar Starts / Breadcrumb Area  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('lang_v1.sales_commission_agents')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <div class="layout-px-spacing">
        <div class="layout-top-spacing mb-2">
            <div class="col-md-12">
                <div class="row">
                    <div class="container p-0">
                        <div class="row layout-top-spacing date-table-container">
                            <!-- All Sales comission agent -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <div class="widget-content widget-content-area br-6">
                                    <div class="table-header">
                                        <h4>@lang('lang_v1.sales_commission_agents')</h4>
                                        @can('user.create')
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-primary btn-modal pull-right"
                                                    data-href="{{ action([\App\Http\Controllers\SalesCommissionAgentController::class, 'create']) }}"
                                                    data-container=".commission_agent_modal"><i class="la la-plus"></i>
                                                    @lang('messages.add')
                                                </button>


                                            </div>
                                        @endcan
                                    </div>
                                    @can('user.view')
                                        <div class="table-responsive mb-4">
                                            <table id="sales_commission_agent_table" class="table table-hover"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('user.name')</th>
                                                        <th>@lang('business.email')</th>
                                                        <th>@lang('lang_v1.contact_no')</th>
                                                        <th>@lang('business.address')</th>
                                                        <th>@lang('lang_v1.cmmsn_percent')</th>
                                                        <th style="width:150px;">@lang('messages.action')</th>
                                                    </tr>
                                                </thead>

                                                <tfoot>
                                                    <tr>
                                                        <th>@lang('user.name')</th>
                                                        <th>@lang('business.email')</th>
                                                        <th>@lang('lang_v1.contact_no')</th>
                                                        <th>@lang('business.address')</th>
                                                        <th>@lang('lang_v1.cmmsn_percent')</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="modal  commission_agent_modal" tabindex="-1" role="dialog"
                                aria-labelledby="gridSystemModalLabel">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/Main content-->

@stop
@push('plugin-scripts')
    {!! Html::script('assets/js/loader.js') !!}
    {!! Html::script('plugins/table/datatable/datatables.js') !!}
    <!--  The following JS library files are loaded to use Copy CSV Excel Print Options-->
    {!! Html::script('plugins/table/datatable/button-ext/dataTables.buttons.min.js') !!}
    {!! Html::script('plugins/table/datatable/button-ext/jszip.min.js') !!}
    {!! Html::script('plugins/table/datatable/button-ext/buttons.html5.min.js') !!}
    {!! Html::script('plugins/table/datatable/button-ext/buttons.print.min.js') !!}
    <!-- The following JS library files are loaded to use PDF Options-->
    {!! Html::script('plugins/table/datatable/button-ext/pdfmake.min.js') !!}
    {!! Html::script('plugins/table/datatable/button-ext/vfs_fonts.js') !!}
@endpush
@push('custom-scripts')
    <script type="text/javascript"></script>
@endpush
