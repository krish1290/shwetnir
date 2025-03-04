@extends('layouts.app')
@section('title', __('purchase.purchases'))

@section('content')

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('purchase.purchases')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>


    <!-- Main content -->
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
                                    @component('components.filters', ['title' => __('report.filters')])
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('purchase_list_filter_location_id', __('purchase.business_location') . ':') !!}
                                                {!! Form::select('purchase_list_filter_location_id', $business_locations, null, [
                                                    'class' => 'form-control select2',
                                                    'style' => 'width:100%',
                                                    'placeholder' => __('lang_v1.all'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('purchase_list_filter_supplier_id', __('purchase.supplier') . ':') !!}
                                                {!! Form::select('purchase_list_filter_supplier_id', $suppliers, null, [
                                                    'class' => 'form-control select2',
                                                    'style' => 'width:100%',
                                                    'placeholder' => __('lang_v1.all'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('purchase_list_filter_status', __('purchase.purchase_status') . ':') !!}
                                                {!! Form::select('purchase_list_filter_status', $orderStatuses, null, [
                                                    'class' => 'form-control select2',
                                                    'style' => 'width:100%',
                                                    'placeholder' => __('lang_v1.all'),
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('purchase_list_filter_payment_status', __('purchase.payment_status') . ':') !!}
                                                {!! Form::select(
                                                    'purchase_list_filter_payment_status',
                                                    [
                                                        'paid' => __('lang_v1.paid'),
                                                        'due' => __('lang_v1.due'),
                                                        'partial' => __('lang_v1.partial'),
                                                        'overdue' => __('lang_v1.overdue'),
                                                    ],
                                                    null,
                                                    ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')],
                                                ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('purchase_list_filter_date_range', __('report.date_range') . ':') !!}
                                                {!! Form::text('purchase_list_filter_date_range', null, [
                                                    'placeholder' => __('lang_v1.select_a_date_range'),
                                                    'class' => 'form-control',
                                                    'readonly',
                                                ]) !!}
                                            </div>
                                        </div>
                                    @endcomponent

                                    @component('components.widget', ['class' => 'box-primary', 'title' => __('purchase.all_purchases')])
                                        @can('purchase.create')
                                            <div class="table-header">

                                                <div class="box-tools">
                                                    <a class="btn btn-block btn-primary"
                                                        href="{{ action([\App\Http\Controllers\PurchaseController::class, 'create']) }}">
                                                        <i class="fa fa-plus"></i> @lang('messages.add')</a>
                                                </div>
                                            </div>
                                        @endcan
                                        @include('purchase.partials.purchase_table')
                                    @endcomponent

                                    <div class="modal fade product_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
                                    </div>

                                    <div class="modal fade payment_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
                                    </div>

                                    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
                                    </div>

                                    @include('purchase.partials.update_purchase_status_modal')
                                    @include('purchase.partials.update_purchase_approve_status_modal')

                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <section id="receipt_section" class="print_section"></section> --}}

    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script>
        //Date range as a button
        $('#purchase_list_filter_date_range').daterangepicker(
            dateRangeSettings,
            function(start, end) {
                $('#purchase_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                    moment_date_format));
                purchase_table.ajax.reload();
            }
        );
        $('#purchase_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#purchase_list_filter_date_range').val('');
            purchase_table.ajax.reload();
        });

        $(document).on('click', '.update_status', function(e) {
            e.preventDefault();
            $('#update_purchase_status_form').find('#status').val($(this).data('status'));
            $('#update_purchase_status_form').find('#purchase_id').val($(this).data('purchase_id'));
            $('#update_purchase_status_modal').modal('show');
        });

        $(document).on('submit', '#update_purchase_status_form', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: data,
                beforeSend: function(xhr) {
                    __disable_submit_button(form.find('button[type="submit"]'));
                },
                success: function(result) {
                    if (result.success == true) {
                        $('#update_purchase_status_modal').modal('hide');
                        toastr.success(result.msg);
                        purchase_table.ajax.reload();
                        $('#update_purchase_status_form')
                            .find('button[type="submit"]')
                            .attr('disabled', false);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });


        $(document).on('click', '.update_approve_status', function(e) {
            e.preventDefault();
            var status = $(this).data('approve');
            var approve_purchase_id = $(this).data('purchase_id');
            console.log(status,approve_purchase_id);
            $('#approve_status').val(status);
            $('#approve_purchase_id').val(approve_purchase_id);
            $('#update_purchase_approve_status_modal').modal('show');
        });

        $(document).on('submit', '#update_purchase_approve_status_form', function(e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();

            $.ajax({
                method: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: data,
                beforeSend: function(xhr) {
                    __disable_submit_button(form.find('button[type="submit"]'));
                },
                success: function(result) {
                    if (result.success == true) {
                        $('#update_purchase_approve_status_modal').modal('hide');
                        toastr.success(result.msg);
                        purchase_table.ajax.reload();
                        $('#update_purchase_approve_status_form')
                            .find('button[type="submit"]')
                            .attr('disabled', false);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });
    </script>

@endsection
