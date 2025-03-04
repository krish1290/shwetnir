<section class="no-print" style="margin:20px 0px 20px 0px;">
    <nav id="navbar-example-one" class="navbar navbar-light bg-light" style="display:block">

        <ul class="nav nav-pills">
            @can('essentials.crud_leave_type')
                <li @if (request()->segment(2) == 'leave-type') class="active" @endif><a
                        href="{{ action([\Modules\Essentials\Http\Controllers\EssentialsLeaveTypeController::class, 'index']) }}">@lang('essentials::lang.leave_type')</a>
                </li>
            @endcan
            @if (auth()->user()->can('essentials.crud_all_leave') ||
                    auth()->user()->can('essentials.crud_own_leave'))
                <li @if (request()->segment(2) == 'leave') class="active" @endif><a
                        href="{{ action([\Modules\Essentials\Http\Controllers\EssentialsLeaveController::class, 'index']) }}">@lang('essentials::lang.leave')</a>
                </li>
            @endif
            @if (auth()->user()->can('essentials.view_all_attendance') ||
                    auth()->user()->can('essentials.view_own_attendance'))
                <li @if (request()->segment(2) == 'attendance') class="active" @endif><a
                        href="{{ action([\Modules\Essentials\Http\Controllers\AttendanceController::class, 'index']) }}">@lang('essentials::lang.attendance')</a>
                </li>
            @endif
            <li @if (request()->segment(2) == 'payroll') class="active" @endif><a
                    href="{{ action([\Modules\Essentials\Http\Controllers\PayrollController::class, 'index']) }}">@lang('essentials::lang.payroll')</a>
            </li>

            <li @if (request()->segment(2) == 'holiday') class="active" @endif><a
                    href="{{ action([\Modules\Essentials\Http\Controllers\EssentialsHolidayController::class, 'index']) }}">@lang('essentials::lang.holiday')</a>
            </li>
            @can('essentials.crud_department')
                <li @if (request()->get('type') == 'hrm_department') class="active" @endif><a
                        href="{{ action([\App\Http\Controllers\TaxonomyController::class, 'index']) . '?type=hrm_department' }}">@lang('essentials::lang.departments')</a>
                </li>
            @endcan

            @can('essentials.crud_designation')
                <li @if (request()->get('type') == 'hrm_designation') class="active" @endif><a
                        href="{{ action([\App\Http\Controllers\TaxonomyController::class, 'index']) . '?type=hrm_designation' }}">@lang('essentials::lang.designations')</a>
                </li>
            @endcan

            @if (auth()->user()->can('essentials.access_sales_target'))
                <li @if (request()->segment(1) == 'hrm' && request()->segment(2) == 'sales-target') class="active" @endif><a
                        href="{{ action([\Modules\Essentials\Http\Controllers\SalesTargetController::class, 'index']) }}">@lang('essentials::lang.sales_target')</a>
                </li>
            @endif

            @if (auth()->user()->can('edit_essentials_settings'))
                <li @if (request()->segment(1) == 'hrm' && request()->segment(2) == 'settings') class="active" @endif><a
                        href="{{ action([\Modules\Essentials\Http\Controllers\EssentialsSettingsController::class, 'edit']) }}">@lang('business.settings')</a>
                </li>
            @endif
        </ul>
    </nav>
</section>
