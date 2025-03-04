@extends('layouts.app')
@section('title', __('lang_v1.customer_groups'))
@push('plugin-styles')
@endpush
@section('content')

    <!-- Content Header (Page header) -->
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('lang_v1.customer_groups')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <div class="layout-px-spacing">
        <div class="layout-top-spacing mb-2">
            <div class="col-md-12">
                <div class="row">
                    <div class="container p-0">
                        <div class="row layout-top-spacing date-table-container">
                            <!-- All Contacts -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_your_customer_groups')])
                                    @can('customer.create')
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-block btn-primary btn-modal"
                                                    data-href="{{ action([\App\Http\Controllers\CustomerGroupController::class, 'create']) }}"
                                                    data-container=".customer_groups_modal">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                            </div>
                                        </div>
                                    @endcan
                                    @can('customer.view')
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="customer_groups_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('lang_v1.customer_group_name')</th>
                                                        <th>@lang('lang_v1.calculation_percentage')</th>
                                                        <th>@lang('lang_v1.selling_price_group')</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    @endcan
                                @endcomponent

                                <div class="modal fade customer_groups_modal" tabindex="-1" role="dialog"
                                    aria-labelledby="gridSystemModalLabel">
                                </div>
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
@endpush
@push('custom-scripts')
    <script type="text/javascript">
        $(document).on('change', '#price_calculation_type', function() {
            var price_calculation_type = $(this).val();

            if (price_calculation_type == 'percentage') {
                $('.percentage-field').removeClass('hide');
                $('.selling_price_group-field').addClass('hide');
            } else {
                $('.percentage-field').addClass('hide');
                $('.selling_price_group-field').removeClass('hide');
            }
        })
    </script>
@endpush
