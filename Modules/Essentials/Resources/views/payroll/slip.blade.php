@extends('layouts.payroll')
@section('title', 'Pay slip')
@section('content')

    <table class="table table-bordered" id="payroll-view">
        <tr>
            <td colspan="3">
                <table width="100%" class="child-table">
                    <tr>
                        <td width="50%">
                            @if (!empty(Session::get('business.logo')))
                                <img src="{{ asset('uploads/business_logos/' . Session::get('business.logo')) }}"
                                    alt="Logo" style="width: auto; max-height: 50px; margin: auto;">
                            @endif
                        </td>
                        <td width="50%" style="text-align:end">
                            <strong style="font-size:23px;">
                                {{ Session::get('business.name') ?? '' }}
                            </strong>
                            <br>
                            
                                {{ Session::get('business.account_no') ?? '' }}
                            
                            <br>
                            {!! Session::get('business.business_address') ?? '' !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center">
                            @lang('essentials::lang.payslip_for_the_month', ['month' => $month_name, 'year' => $year])
                        </td>
                    <tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table width="100%" class="child-table">
                    <tr>
                        <td width="50%">
                            <strong>@lang('essentials::lang.employee'):</strong>
                            {{ $payroll->transaction_for->user_full_name }}<br>

                            <strong>@lang('essentials::lang.department'):</strong>
                            {{ $department->name ?? '' }}
                            <br>

                            <strong>@lang('essentials::lang.designation'):</strong>
                            {{ $designation->name ?? '' }}

                            <br>
                            <strong>@lang('lang_v1.primary_work_location'):</strong>
                            @if (!empty($location))
                                {{ $location->name }}
                            @else
                                {{ __('report.all_locations') }}
                            @endif
                            <br>

                            @if (!empty($payroll->transaction_for->id_proof_name) && !empty($payroll->transaction_for->id_proof_number))
                                <strong>
                                    {{ ucfirst($payroll->transaction_for->id_proof_name) }}:
                                </strong>
                                {{ $payroll->transaction_for->id_proof_number }}
                                <br>
                            @endif

                            <strong>@lang('lang_v1.tax_payer_id'):</strong>
                            {{ $bank_details['tax_payer_id'] ?? '' }}
                            <br>
                        </td>
                        <td width="50%">
                            <strong>@lang('lang_v1.bank_name'):</strong>
                            {{ $bank_details['bank_name'] ?? '' }}
                            <br>

                            <strong>@lang('lang_v1.branch'):</strong>
                            {{ $bank_details['branch'] ?? '' }}
                            <br>

                            <strong>@lang('lang_v1.bank_code'):</strong>
                            {{ $bank_details['bank_code'] ?? '' }}
                            <br>

                            <strong>@lang('lang_v1.account_holder_name'):</strong>
                            {{ $bank_details['account_holder_name'] ?? '' }}
                            <br>

                            <strong>@lang('lang_v1.bank_account_no'):</strong>
                            {{ $bank_details['account_number'] ?? '' }}
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <strong>@lang('essentials::lang.total_work_duration'):</strong>
                {{ (int) $total_work_duration }} Hours
            </td>
            <td>
                <strong>@lang('essentials::lang.paid_days'):</strong>
                {{ $total_days_present }}
            </td>
            <td>
                <strong>@lang('essentials::lang.lop_days'):</strong>
                {{ $days_in_a_month - $total_days_present }}
            </td>
        </tr>


        <!--  earning entries   -->
        <tr>
            <td colspan="3" style="padding:0px;border:0px;">
                <table width="100%" class="child-table has_border">
                    <tr>
                        <th style="width: 30% !important;">
                            <strong>@lang('essentials::lang.allowances')</strong>
                        </th>
                        <th style="width: 20% !important;">
                            <strong>@lang('essentials::lang.rate')</strong>
                        </th>
                        <th style="width: 30% !important;">
                            <strong>@lang('sale.amount')</strong>
                        </th>
                        <th style="width: 20% !important;">
                            <strong>YTD</strong>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 50% !important; ">
                            @php
                                //$total_earnings = $payroll->essentials_duration * $payroll->essentials_amount_per_unit_duration;
                                $total_earnings = $payroll->essentials_unit_salary;
                                $total_earning_ytd = $ytd_payroll['basic']['basic_salary'];
                            @endphp
                            @lang('essentials::lang.salary')
                        </td>

                        <td style="width: 30% !important;">
                            <span class="display_currency" data-currency_symbol="true">
                                {{ session('currency')['symbol'] }}
                                {{ @num_format($payroll->essentials_unit_salary) }}
                            </span>
                            <br>
                        </td>
                        <td style="width: 20% !important;">
                            <span class="display_currency" data-currency_symbol="true">
                                {{ session('currency')['symbol'] }}
                                {{ @num_format($ytd_payroll['basic']['basic_salary']) }}
                            </span>
                            <br>
                        </td>
                    </tr>

                    @forelse($allowances['allowance_names'] as $key => $value)
                        <tr>
                            <td style="width: 30% !important; ">
                                {{ $value }}
                            </td>
                            <td style="width: 20% !important;">
                                @if (!empty($allowances['allowance_types'][$key]) && $allowances['allowance_types'][$key] == 'percent')
                                    {{ @num_format($allowances['allowance_percents'][$key]) }}%
                                @endif
                            </td>
                            <td style="width: 30% !important;">
                                <span class="display_currency" data-currency_symbol="true">
                                    {{ session('currency')['symbol'] }}
                                    {{ @num_format($allowances['allowance_amounts'][$key]) }}
                                </span>
                            </td>

                            <td style="width: 20% !important;">
                                <span class="display_currency" data-currency_symbol="true">
                                    @if (array_key_exists($value, $ytd_payroll['allowance']))
                                        {{ session('currency')['symbol'] }}
                                        {{ @num_format($ytd_payroll['allowance'][$value]) }}
                                    @endif
                                </span>
                                @php
                                    $total_earning_ytd += !empty($ytd_payroll['allowance'][$value]) ? $ytd_payroll['allowance'][$value] : 0;
                                    $total_earnings += !empty($allowances['allowance_amounts'][$key]) ? $allowances['allowance_amounts'][$key] : 0;
                                @endphp
                            </td>

                        </tr>
                    @empty
                    @endforelse
                </table>
            </td>
        </tr>
        <!-- total earning   -->
        <tr>
            <td colspan="3">
                <table width="100%" class="child-table">
                    <tr>
                        <th style="width: 30% !important; ">

                        </th>
                        <th style="width: 20% !important; ">
                            <strong>
                                @lang('essentials::lang.total_earnings'):
                            </strong>
                        </th>
                        <th style="width: 15% !important;">
                            <strong>
                                <span class="display_currency" data-currency_symbol="true">
                                    {{ session('currency')['symbol'] }}
                                    {{ @num_format($total_earnings) }}
                                </span>
                            </strong>
                        </th>
                        <th style="width: 15% !important; text-align:right">
                            <strong>
                                <span>
                                    Earning YTD:
                                </span>
                            </strong>
                        </th>
                        <th style="width: 20% !important;">
                            <span class="display_currency" data-currency_symbol="true">
                                {{ session('currency')['symbol'] }}
                                {{ @num_format($total_earning_ytd) }}
                            </span>
                        </th>
                    </tr>
                </table>
            </td>

        </tr>
        <!--  deduction entries   -->
        <tr>
            <td colspan="3" style="padding:0px;border:0px;">
                @php
                    $total_deduction = 0;
                    $total_deduction_ytd = 0;
                @endphp
                <table width="100%" class="child-table has_border">
                    <tr>
                        <th style="width: 30% !important; ">
                            <strong>@lang('essentials::lang.deductions')</strong>
                        </th>
                        <th style="width: 20% !important;">
                            <strong>@lang('essentials::lang.rate')</strong>
                        </th>
                        <th style="width: 30% !important;">
                            <strong>@lang('sale.amount')</strong>
                        </th>

                        <th style="width: 20% !important;">
                            <strong>YTD</strong>
                        </th>
                    </tr>
                    @forelse($deductions['deduction_names'] as $key => $value)
                        <tr>
                            <td style="width: 30% !important; ">
                                {{ $value }}
                            </td>
                            <td style="width: 20% !important;">
                                @if (!empty($deductions['deduction_types'][$key]) && $deductions['deduction_types'][$key] == 'percent')
                                    {{ @num_format($deductions['deduction_percents'][$key]) }}%
                                @endif
                                @php
                                    $total_deduction_ytd += !empty($ytd_payroll['deduction'][$value]) ? $ytd_payroll['deduction'][$value] : 0;
                                    $total_deduction += !empty($deductions['deduction_amounts'][$key]) ? $deductions['deduction_amounts'][$key] : 0;
                                @endphp
                            </td>
                            <td style="width: 30% !important;">
                                <span class="display_currency" data-currency_symbol="true">
                                    {{ session('currency')['symbol'] }}
                                    {{ @num_format($deductions['deduction_amounts'][$key]) }}
                                </span>
                            </td>

                            <td style="width: 20% !important;">
                                <span class="display_currency" data-currency_symbol="true">
                                    @if (array_key_exists($value, $ytd_payroll['deduction']))
                                        {{ session('currency')['symbol'] }}
                                        {{ @num_format($ytd_payroll['deduction'][$value]) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="width: 100% !important; text-align: center;">
                                @lang('lang_v1.none')
                            </td>
                        <tr>
                    @endforelse
                </table>
            </td>
        </tr>

        <!-- total deduction   -->
        <tr>
            <td colspan="3">
                <table width="100%" class="child-table">
                    <tr>
                        <th style="width: 30% !important; ">

                        </th>
                        <th style="width: 20% !important; ">
                            <strong>
                                @lang('essentials::lang.total_deductions'):
                            </strong>
                        </th>
                        <th style="width: 13% !important;">
                            <strong>
                                <span class="display_currency" data-currency_symbol="true">
                                    {{ session('currency')['symbol'] }}
                                    {{ @num_format($total_deduction) }}
                                </span>
                            </strong>
                        </th>
                        <th style="width: 15% !important; text-align:right">
                            <strong>
                                <span>
                                    Deducted YTD:
                                </span>
                            </strong>
                        </th>
                        <th style="width: 20% !important;">
                            <span class="display_currency" data-currency_symbol="true">
                                {{ session('currency')['symbol'] }}
                                {{ @num_format($total_deduction_ytd) }}
                            </span>
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- net pay  -->
        <tr>
            <td colspan="3">
                <table width="100%" class="child-table">
                    <tr>
                        <th style="width: 30% !important; ">

                        </th>
                        <th style="width: 20% !important; ">
                            <strong>
                                @lang('essentials::lang.net_pay'):
                            </strong>
                        </th>
                        <th style="width: 15% !important;">
                            <strong>
                                <span class="display_currency" data-currency_symbol="true">
                                    {{ session('currency')['symbol'] }}
                                    {{ @num_format($total_earnings - $total_deduction) }}
                                </span>
                            </strong>
                        </th>
                        <th style="width: 15% !important; text-align:right">
                            <strong>
                                <span>
                                    Total YTD:
                                </span>
                            </strong>
                        </th>
                        <th style="width: 20% !important;">
                            <span class="display_currency" data-currency_symbol="true">
                                {{ session('currency')['symbol'] }}
                                {{ @num_format($total_earning_ytd - $total_deduction_ytd) }}
                            </span>
                        </th>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td colspan="3">
                <strong>@lang('essentials::lang.in_words'):</strong> {{ ucfirst($final_total_in_words) }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>{{ __('sale.payment_info') }}:</strong>
                <table class="table bg-gray table-slim">
                    <tr class="bg-green">
                        <th>#</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('purchase.ref_no') }}</th>
                        <th>{{ __('sale.amount') }}</th>
                        <th>{{ __('sale.payment_mode') }}</th>
                        <th>{{ __('sale.payment_note') }}</th>
                    </tr>
                    @php
                        $total_paid = 0;
                    @endphp
                    @forelse($payroll->payment_lines as $payment_line)
                        @php
                            if ($payment_line->is_return == 1) {
                                $total_paid -= $payment_line->amount;
                            } else {
                                $total_paid += $payment_line->amount;
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ @format_date($payment_line->paid_on) }}</td>
                            <td>{{ $payment_line->payment_ref_no }}</td>
                            <td><span class="display_currency"
                                    data-currency_symbol="true">{{ $payment_line->amount }}</span></td>
                            <td>
                                {{ $payment_types[$payment_line->method] }}
                            </td>
                            <td>
                                @if ($payment_line->note)
                                    {{ ucfirst($payment_line->note) }}
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">@lang('purchase.no_records_found')</td>
                        </tr>
                    @endforelse
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>@lang('brand.note'):</strong><br>
                {{ $payroll->staff_note ?? '' }}
            </td>
        </tr>
    </table>

@stop
<style>
    #clock_in_clock_out_modal {
        display: none;
    }

    html,
    body {
        margin: 10px;
        padding: 10px;
        font-family: sans-serif;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    span,
    label {
        font-family: sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0px !important;
    }

    .child-table {
        width: 100%;
        margin-bottom: 0px !important;
    }



    table thead th {
        height: 28px;
        text-align: left;
        font-size: 16px;
        font-family: sans-serif;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 14px;
    }



    .child-table th,
    .child-table td {
        border: 0px solid #ddd;
        padding: 2px;
        font-size: 14px;
        text-align: left;
    }

    .has_border th,
    .has_border td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .heading {
        font-size: 24px;
        margin-top: 12px;
        margin-bottom: 12px;
        font-family: sans-serif;
    }

    .small-heading {
        font-size: 18px;
        font-family: sans-serif;
    }

    .total-heading {
        font-size: 18px;
        font-weight: 700;
        font-family: sans-serif;
    }

    .order-details tbody tr td:nth-child(1) {
        width: 20%;
    }

    .order-details tbody tr td:nth-child(3) {
        width: 20%;
    }

    .text-start {
        text-align: left;
    }

    .text-end {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .company-data span {
        margin-bottom: 4px;
        display: inline-block;
        font-family: sans-serif;
        font-size: 14px;
        font-weight: 400;
    }

    .no-border {
        border: 1px solid #fff !important;
    }

    .bg-blue {
        background-color: #414ab1;
        color: #fff;
    }
</style>
