@extends('layouts.app')
@section('title', __('unit.units'))

@section('content')

    <!-- Content Header (Page header) -->

    <div class="sub-header-container  no-print" style="margin-bottom:40px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('products') }}">@lang('sale.products')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('unit.manage_your_units')</span></li>
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

                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                @component('components.widget', ['class' => 'box-primary', 'title' => __('unit.all_your_units')])
                                    @can('unit.create')
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-block btn-primary btn-modal"
                                                    data-href="{{ action([\App\Http\Controllers\UnitController::class, 'create']) }}"
                                                    data-container=".unit_modal">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                            </div>
                                        </div>
                                    @endcan
                                    @can('unit.view')
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="unit_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('unit.name')</th>
                                                        <th>@lang('unit.short_name')</th>
                                                        <th>@lang('unit.allow_decimal') @show_tooltip(__('tooltip.unit_allow_decimal'))</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    @endcan
                                @endcomponent

                                <div class="modal fade unit_modal" tabindex="-1" role="dialog"
                                    aria-labelledby="gridSystemModalLabel">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

@endsection
