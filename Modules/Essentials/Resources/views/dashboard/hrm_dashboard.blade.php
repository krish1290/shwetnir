@extends('layouts.app')
@section('title', __('essentials::lang.hrm'))

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
                                <li class="breadcrumb-item"><a href="javasript:void()">@lang('essentials::lang.hrm')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    @include('essentials::layouts.nav_hrm')
    <!-- Main content -->
    <section class="content">
        <div class="row row-custom">
            <div class="col-md-4 col-sm-6 col-xs-12 col-custom">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fas fa-sign-out-alt"></i>
                        <h3 class="box-title">@lang('essentials::lang.my_leaves')</h3>
                    </div>
                    <div class="box-body p-10">
                        <table class="table no-margin">
                            <thead>
                                @forelse($users_leaves as $user_leave)
                                    <tr>
                                        <td>
                                            {{ @format_date($user_leave->start_date) }}
                                            - {{ @format_date($user_leave->end_date) }}
                                        </td>
                                        <td>
                                            {{ $user_leave->leave_type->leave_type }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            @lang('lang_v1.no_data')
                                        </td>
                                    </tr>
                                @endforelse
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 col-custom">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <i class="fas fa-bullseye"></i>
                        <h3 class="box-title">@lang('essentials::lang.my_sales_targets')</h3>
                    </div>
                    <div class="box-body p-10">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <td>
                                        <strong>@lang('essentials::lang.target_achieved_last_month'):
                                        </strong>
                                        <h4 class="text-success">@format_currency($target_achieved_last_month)</h4>
                                    </td>
                                    <td>
                                        <strong>@lang('essentials::lang.target_achieved_this_month'):
                                        </strong>
                                        <h4 class="text-success">@format_currency($target_achieved_this_month)</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        @lang('essentials::lang.targets')
                                    </th>
                                    <th>
                                        @lang('essentials::lang.commission_percent')
                                    </th>
                                </tr>
                                @forelse($sales_targets as $target)
                                    <tr>
                                        <td>
                                            @format_currency($target->target_start)
                                            - @format_currency($target->target_end)
                                        </td>
                                        <td>
                                            {{ number_format($target->commission_percent, 2) }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            @lang('lang_v1.no_data')
                                        </td>
                                    </tr>
                                @endforelse
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @if (!$is_admin)
                @include('essentials::dashboard.holidays')
            @endif
            <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                <a href="{{ action([\Modules\Essentials\Http\Controllers\PayrollController::class, 'getMyPayrolls']) }}"
                    class="btn btn-lg btn-success">
                    <i class="fas fa-coins"></i>
                    @lang('essentials::lang.my_payrolls')
                </a>
            </div>
        </div>
        @if ($is_admin)
            <hr>
        @endif
        <div class="row row-custom">
            @can('user.view')
                <div class="col-md-4 col-sm-6 col-xs-12 col-custom">
                    <div class="box box-solid">
                        <div class="box-body p-10">
                            <div class="info-box info-box-new-style">
                                <span class="info-box-icon bg-aqua"><i class="fas fa-users"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('user.users') }}</span>
                                    <span class="info-box-number">{{ $users->count() }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>{{ __('essentials::lang.department') }}</th>
                                        <th>{{ __('sale.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departments as $department)
                                        <tr>
                                            <td>{{ $department->name }}</td>
                                            <td>
                                                @if (!empty($users_by_dept[$department->id]))
                                                    {{ count($users_by_dept[$department->id]) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">@lang('lang_v1.no_data')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endcan
            @can('essentials.approve_leave')

                <div class="col-md-4 col-sm-6 col-xs-12 col-custom">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fas fa-user-times"></i>
                            <h3 class="box-title">@lang('essentials::lang.leaves')</h3>
                        </div>
                        <div class="box-body p-10">
                            <table class="table no-margin">
                                <tr>
                                    <th class="bg-light-gray" colspan="2">@lang('home.today')</th>
                                </tr>
                                @forelse($todays_leaves as $leave)
                                    <tr>
                                        <td>
                                            {{ @format_date($leave->start_date) }}
                                            - {{ @format_date($leave->end_date) }}
                                        </td>
                                        <td>
                                            {{ $leave->leave_type->leave_type }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            @lang('lang_v1.no_data')
                                        </td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th class="bg-light-gray" colspan="2">@lang('lang_v1.upcoming')</th>
                                </tr>
                                @forelse($upcoming_leaves as $leave)
                                    <tr>
                                        <td>
                                            {{ @format_date($leave->start_date) }}
                                            - {{ @format_date($leave->end_date) }}
                                        </td>
                                        <td>
                                            {{ $leave->leave_type->leave_type }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            @lang('lang_v1.no_data')
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            @endcan
            @if ($is_admin)
                @include('essentials::dashboard.holidays')
            @endif
        </div>
        <div class="row row-custom">
            @if ($is_admin)
                <div class="col-md-4 col-sm-6 col-xs-12 col-custom">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fas fa-user-check"></i>
                            <h3 class="box-title">@lang('essentials::lang.todays_attendance')</h3>
                        </div>
                        <div class="box-body p-10">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>
                                            @lang('essentials::lang.employee')
                                        </th>
                                        <th>
                                            @lang('essentials::lang.clock_in')
                                        </th>
                                        <th>
                                            @lang('essentials::lang.clock_out')
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($todays_attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->employee->user_full_name }}</td>
                                            <td>
                                                {{ @format_datetime($attendance->clock_in_time) }}

                                                @if (!empty($attendance->clock_in_note))
                                                    <br><small>{{ $attendance->clock_in_note }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($attendance->clock_out_time))
                                                    {{ @format_datetime($attendance->clock_out_time) }}
                                                @endif

                                                @if (!empty($attendance->clock_out_note))
                                                    <br><small>{{ $attendance->clock_out_note }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">@lang('lang_v1.no_data')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-sm-12 col-xs-12 date-table-container">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fas fa-bullseye"></i>
                            <h3 class="box-title">@lang('essentials::lang.sales_targets')</h3>
                        </div>
                        <div class="box-body">
                            <table class="table" id="sales_targets_table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>@lang('report.user')</th>
                                        <th>@lang('essentials::lang.target_achieved_last_month')</th>
                                        <th>@lang('essentials::lang.target_achieved_this_month')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </section>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#sales_targets_table').length) {
                var sales_targets_table = $('#sales_targets_table').DataTable({
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
                    processing: true,
                    serverSide: true,
                    searching: false,
                    scrollY: "75vh",
                    scrollX: true,
                    scrollCollapse: true,
                    dom: 'Btirp',
                    fixedHeader: false,
                    ajax: "{{ action([\Modules\Essentials\Http\Controllers\DashboardController::class, 'getUserSalesTargets']) }}"
                });
            }
        });
    </script>
@endsection
