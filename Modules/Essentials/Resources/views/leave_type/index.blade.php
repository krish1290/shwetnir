@extends('layouts.app')
@section('title', __('essentials::lang.leave_type'))

@section('content')
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
                                <li class="breadcrumb-item"><a href="{{ url('hrm/dashboard') }}">@lang('essentials::lang.hrm')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('essentials::lang.leave_type')</span>
                                </li>

                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    @include('essentials::layouts.nav_hrm')


    <!-- Main content -->
    <section class="content date-table-container">
        @component('components.widget', ['class' => 'box-solid', 'title' => __('essentials::lang.all_leave_types')])
            <div class="table-header">
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary" data-toggle="modal"
                        data-target="#add_leave_type_modal">
                        <i class="fa fa-plus"></i> @lang('messages.add')</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="leave_type_table">
                    <thead>
                        <tr>
                            <th>@lang('essentials::lang.leave_type')</th>
                            <th>@lang('essentials::lang.max_leave_count')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcomponent

        @include('essentials::leave_type.create')

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            leave_type_table = $('#leave_type_table').DataTable({
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
                ajax: "{{ action([\Modules\Essentials\Http\Controllers\EssentialsLeaveTypeController::class, 'index']) }}",
                columnDefs: [{
                    targets: 2,
                    orderable: false,
                    searchable: false,
                }, ],
            });

        });

        $(document).on('submit', 'form#add_leave_type_form, form#edit_leave_type_form', function(e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (result.success == true) {
                        $('div#add_leave_type_modal').modal('hide');
                        $('.view_modal').modal('hide');
                        toastr.success(result.msg);
                        leave_type_table.ajax.reload();
                        $('form#add_leave_type_form')[0].reset();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        })
    </script>
@endsection
