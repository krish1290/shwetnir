@extends('layouts.app')
@section('title', __('lang_v1.warranties'))

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
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('lang_v1.warranties')</span></li>
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
                            <!-- All warranties -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_warranties')])
                                    <div class="table-header">
                                        <div class="box-tools">
                                            <button type="button" class="btn btn-block btn-primary btn-modal"
                                                data-href="{{ action([\App\Http\Controllers\WarrantyController::class, 'create']) }}"
                                                data-container=".view_modal">
                                                <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped" id="warranty_table">
                                        <thead>
                                            <tr>
                                                <th>@lang('lang_v1.name')</th>
                                                <th>@lang('lang_v1.description')</th>
                                                <th>@lang('lang_v1.duration')</th>
                                                <th>@lang('messages.action')</th>
                                            </tr>
                                        </thead>
                                    </table>
                                @endcomponent

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
            //Status table
            var warranty_table = $('#warranty_table').DataTable({
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
                ajax: "{{ action([\App\Http\Controllers\WarrantyController::class, 'index']) }}",
                columnDefs: [{
                    "targets": 3,
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'duration',
                        name: 'duration'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            $(document).on('submit', 'form#warranty_form', function(e) {
                e.preventDefault();
                $(this).find('button[type="submit"]').attr('disabled', true);
                var data = $(this).serialize();

                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: data,
                    success: function(result) {
                        if (result.success == true) {
                            $('div.view_modal').modal('hide');
                            toastr.success(result.msg);
                            warranty_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });
        });
    </script>
@endsection
