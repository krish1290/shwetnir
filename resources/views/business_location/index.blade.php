@extends('layouts.app')
@section('title', __('business.business_locations'))

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
                                <li class="breadcrumb-item"><a
                                        href="{{ url('business.business_settings') }}">@lang('business.business_settings')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('business.manage_your_business_locations')</span></li>
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
                    <div class="container p-0 m-0" style="width:1017px;">
                        <div class="row layout-top-spacing date-table-container">
                            <!-- All Products -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <section class="content">
                                    @component('components.widget', ['class' => 'box-primary', 'title' => __('business.all_your_business_locations')])
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-block btn-primary btn-modal"
                                                    data-href="{{ action([\App\Http\Controllers\BusinessLocationController::class, 'create']) }}"
                                                    data-container=".location_add_modal">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="business_location_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('invoice.name')</th>
                                                        <th>@lang('lang_v1.location_id')</th>
                                                        <th>@lang('business.landmark')</th>
                                                        <th>@lang('business.city')</th>
                                                        <th>@lang('business.zip_code')</th>
                                                        <th>@lang('business.state')</th>
                                                        <th>@lang('business.country')</th>
                                                        <th>@lang('lang_v1.price_group')</th>
                                                        <th>@lang('invoice.invoice_scheme')</th>
                                                        <th>@lang('lang_v1.invoice_layout_for_pos')</th>
                                                        <th>@lang('lang_v1.invoice_layout_for_sale')</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    @endcomponent

                                    <div class="modal fade location_add_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
                                    </div>
                                    <div class="modal fade location_edit_modal" tabindex="-1" role="dialog"
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
