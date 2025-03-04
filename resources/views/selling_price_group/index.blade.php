@extends('layouts.app')
@section('title', __('lang_v1.selling_price_group'))

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
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('lang_v1.selling_price_group')</span></li>
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
                                @if (session('notification') || !empty($notification))
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">×</button>
                                                @if (!empty($notification['msg']))
                                                    {{ $notification['msg'] }}
                                                @elseif(session('notification.msg'))
                                                    {{ session('notification.msg') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @component('components.widget', [
                                    'class' => 'box-primary',
                                    'title' => __('lang_v1.import_export_selling_price_group_prices'),
                                ])
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="{{ action([\App\Http\Controllers\SellingPriceGroupController::class, 'export']) }}"
                                                class="btn btn-primary">@lang('lang_v1.export_selling_price_group_prices')</a>
                                        </div>
                                        <div class="col-sm-6">
                                            {!! Form::open([
                                                'url' => action([\App\Http\Controllers\SellingPriceGroupController::class, 'import']),
                                                'method' => 'post',
                                                'enctype' => 'multipart/form-data',
                                            ]) !!}
                                            <div class="form-group">
                                                {!! Form::label('name', __('product.file_to_import') . ':') !!}
                                                {!! Form::file('product_group_prices', ['required' => 'required']) !!}
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="col-sm-12">
                                            <h4>@lang('lang_v1.instructions'):</h4>
                                            <p>
                                                &bull; @lang('lang_v1.price_group_import_istruction')
                                            </p>
                                            <p>
                                                &bull; @lang('lang_v1.price_group_import_istruction1')
                                            </p>
                                            <p>
                                                &bull; @lang('lang_v1.price_group_import_istruction2')
                                            </p>
                                        </div>
                                    </div>
                                @endcomponent
                                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_selling_price_group')])
                                    <div class="table-header">
                                        <div class="box-tools">
                                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                                data-href="{{ action([\App\Http\Controllers\SellingPriceGroupController::class, 'create']) }}"
                                                data-container=".view_modal">
                                                <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="selling_price_group_table">
                                            <thead>
                                                <tr>
                                                    <th>@lang('lang_v1.name')</th>
                                                    <th>@lang('lang_v1.description')</th>
                                                    <th>@lang('messages.action')</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
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
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            //selling_price_group_table
            var selling_price_group_table = $('#selling_price_group_table').DataTable({
                autoWidth: false,
                dom: '<"row"<"col-md-6"B><"col-md-6"f> ><""rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>>',
                buttons: {
                    buttons: [{
                            extend: 'copy',
                            className: 'btn btn-primary'
                        },
                        {
                            extend: 'csv',
                            className: 'btn btn-primary'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-primary'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-primary'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-primary'
                        }
                    ]
                },
                language: {
                    "paginate": {
                        "previous": "<i class='las la-angle-left'></i>",
                        "next": "<i class='las la-angle-right'></i>"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: '/selling-price-group',
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }]
            });

            $(document).on('submit', 'form#selling_price_group_form', function(e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.view_modal').modal('hide');
                            toastr.success(result.msg);
                            selling_price_group_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });

            $(document).on('click', 'button.delete_spg_button', function() {
                swal({
                    title: LANG.sure,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    selling_price_group_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });

            $(document).on('click', 'button.activate_deactivate_spg', function() {
                var href = $(this).data('href');
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            selling_price_group_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });

        });
    </script>
@endsection
