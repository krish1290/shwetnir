@extends('layouts.app')
@section('title', 'Brands')

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
                                <li class="breadcrumb-item"><a href="{{ url('products') }}">@lang('sale.products')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('brand.manage_your_brands')</span></li>
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
                            <!-- All Brands -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                @component('components.widget', ['class' => 'box-primary', 'title' => __('brand.all_your_brands')])
                                    @can('brand.create')
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-block btn-primary btn-modal"
                                                    data-href="{{ action([\App\Http\Controllers\BrandController::class, 'create']) }}"
                                                    data-container=".brands_modal">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                            </div>
                                        </div>
                                    @endcan
                                    @can('brand.view')
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="brands_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('brand.brands')</th>
                                                        <th>@lang('brand.note')</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    @endcan
                                @endcomponent

                                <div class="modal fade brands_modal" tabindex="-1" role="dialog"
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
