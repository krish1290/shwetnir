@extends('layouts.app')
@section('title', __('barcode.barcodes'))

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
                                <li class="breadcrumb-item"><a href="{{ url('barcode.barcodes') }}">@lang('barcode.barcodes')</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('barcode.manage_your_barcodes')</span></li>
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
                                    @component('components.widget', ['class' => 'box-primary', 'title' => __('barcode.all_your_barcode')])
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <a class="btn btn-block btn-primary"
                                                    href="{{ action([\App\Http\Controllers\BarcodeController::class, 'create']) }}">
                                                    <i class="fa fa-plus"></i> @lang('barcode.add_new_setting')</a>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="barcode_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('barcode.setting_name')</th>
                                                        <th>@lang('barcode.setting_description')</th>
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

    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            var barcode_table = $('#barcode_table').DataTable({
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
                processing: true,
                serverSide: true,
                buttons: [],
                ajax: '/barcodes',
                bPaginate: false,
                columnDefs: [{
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                }]
            });
            $(document).on('click', 'button.delete_barcode_button', function() {
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_barcode,
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
                                if (result.success === true) {
                                    toastr.success(result.msg);
                                    barcode_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
            $(document).on('click', 'button.set_default', function() {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: "get",
                    url: href,
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            barcode_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });
        });
    </script>
@endsection
