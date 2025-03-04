@extends('layouts.app')
@section('title', __('product.variations'))

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('lang_v1.manage_product_variations')</a></li>
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
                                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_variations')])
                                    <div class="table-header">
                                        <div class="box-tools">
                                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                                data-href="{{ action([\App\Http\Controllers\VariationTemplateController::class, 'create']) }}"
                                                data-container=".variation_modal">
                                                <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="variation_table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('product.variations')</th>
                                                    <th>@lang('lang_v1.values')</th>
                                                    <th>@lang('messages.action')</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                @endcomponent

                                <div class="modal fade variation_modal" tabindex="-1" role="dialog"
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
    < <!-- /.content -->

    @endsection
