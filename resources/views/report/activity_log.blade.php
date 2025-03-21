@extends('layouts.app')
@section('title', __('lang_v1.activity_log'))

@section('content')

    <!-- Content Header (Page header) -->
    <div class="sub-header-container  no-print"style="margin-bottom-30px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('lang_v1.activity_log')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('al_users_filter', __('lang_v1.by') . ':') !!}
                            {!! Form::select('al_users_filter', $users, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id' => 'al_users_filter',
                                'placeholder' => __('lang_v1.all'),
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('subject_type', __('lang_v1.subject_type') . ':') !!}
                            {!! Form::select('subject_type', $transaction_types, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id' => 'subject_type',
                                'placeholder' => __('lang_v1.all'),
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('al_date_filter', __('report.date_range') . ':') !!}
                            {!! Form::text('al_date_filter', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>

        <div class="row date-table-container">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary'])
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="activity_log_table">
                            <thead>
                                <tr>
                                    <th>@lang('lang_v1.date')</th>
                                    <th>@lang('lang_v1.subject_type')</th>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('lang_v1.by')</th>
                                    <th>@lang('brand.note')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#al_date_filter').daterangepicker(dateRangeSettings, function(start, end) {
                $('#al_date_filter').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                );
                activity_log_table.ajax.reload();
            });
            $('#al_date_filter').on('cancel.daterangepicker', function(ev, picker) {
                $('#al_date_filter').val('');
                activity_log_table.ajax.reload();
            });

            activity_log_table = $('#activity_log_table').DataTable({
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
                aaSorting: [
                    [0, 'desc']
                ],
                "ajax": {
                    "url": '{{ action([\App\Http\Controllers\ReportController::class, 'activityLog']) }}',
                    "data": function(d) {
                        var start_date = '';
                        var end_date = '';
                        if ($('#al_date_filter').val()) {
                            d.start_date = $('input#al_date_filter')
                                .data('daterangepicker')
                                .startDate.format('YYYY-MM-DD');
                            d.end_date = $('input#al_date_filter')
                                .data('daterangepicker')
                                .endDate.format('YYYY-MM-DD');
                        }

                        d.user_id = $('#al_users_filter').val();
                        d.subject_type = $('#subject_type').val();
                    }
                },
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'subject_type',
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    }
                ]
            });

            $(document).on('change', '#al_users_filter, #subject_type', function() {
                activity_log_table.ajax.reload();
            })
        });
    </script>
@endsection
