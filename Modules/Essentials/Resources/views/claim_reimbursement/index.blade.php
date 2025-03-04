@extends('layouts.app')

@section('title', __('essentials::lang.claim_reimbursement'))

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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">@lang('essentials::lang.claim_reimbursement')</a></li>

                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <section class="content date-table-container">
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-solid'])
                    @can('essentials.add_claim_reimbursement')
                        <div class="table-header">
                            <div class="box-tools">
                                <button type="button" class="btn btn-primary btn-modal pull-right"
                                    data-href="{{ action([\Modules\Essentials\Http\Controllers\ClaimReimbursementController::class, 'create']) }}"
                                    data-container="#add_allowance_deduction_modal">
                                    <i class="fa fa-plus"></i> @lang('messages.add')
                                </button>

                            </div>
                        </div>
                    @endcan
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="ad_pc_table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('lang_v1.type')</th>
                                    <th>@lang('sale.amount')</th>
                                    <th>@lang('essentials::lang.date')</th>
                                    <th>@lang('essentials::lang.employee')</th>
                                    <th>status</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="modal fade" id="add_allowance_deduction_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel"></div>
    </section>
    @include('essentials::claim_reimbursement.change_status_modal')
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#add_allowance_deduction_modal').on('shown.bs.modal', function(e) {
                var $p = $(this);
                $('#add_allowance_deduction_modal .select2').select2({
                    dropdownParent: $p
                });
                $('#add_allowance_deduction_modal #applicable_date').datepicker();

            });

            $(document).on('submit', 'form#add_allowance_form', function(e) {
                e.preventDefault();
                //$(this).find('button[type="submit"]').attr('disabled', true);
                var data = new FormData(this);

                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result.success == true) {
                            $('div#add_allowance_deduction_modal').modal('hide');
                            toastr.success(result.msg);
                            ad_pc_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });

            ad_pc_table = $('#ad_pc_table').DataTable({
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
                ajax: "{{ action([\Modules\Essentials\Http\Controllers\ClaimReimbursementController::class, 'index']) }}",
                columns: [{
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'applicable_date',
                        name: 'applicable_date'
                    },
                    {
                        data: 'employees',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'is_approved',
                        searchable: true,
                        orderable: true

                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    __currency_convert_recursively($('#ad_pc_table'));
                },
            });

            $(document).on('click', '.delete-allowance', function(e) {
                e.preventDefault();
                swal({
                    title: LANG.sure,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();

                        $.ajax({
                            method: 'DELETE',
                            url: href,
                            dataType: 'json',
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    ad_pc_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            },
                        });
                    }
                });
            });
            $(document).on('click', 'a.change_status', function(e) {
                e.preventDefault();
                $('#change_status_modal').find('select#status_dropdown').val($(this).data('orig-value'))
                    .change();
                $('#change_status_modal').find('#leave_id').val($(this).data('leave-id'));
                $('#change_status_modal').find('#status_note').val($(this).data('status_note'));
                $('#change_status_modal').modal('show');
            });
            $(document).on('submit', 'form#change_status_form', function(e) {
                e.preventDefault();
                var data = $(this).serialize();
                var ladda = Ladda.create(document.querySelector('.update-leave-status'));
                ladda.start();
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        ladda.stop();
                        if (result.success == true) {
                            $('div#change_status_modal').modal('hide');
                            toastr.success(result.msg);
                            $('#ad_pc_table').DataTable().ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    </script>
@endsection
