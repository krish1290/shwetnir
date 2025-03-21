@extends('layouts.app')
@section('title', __('expense.expenses'))

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('expense.expenses')</a></li>
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
                            <!-- All Sales comission agent -->
                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                <section class="content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @component('components.filters', ['title' => __('report.filters')])
                                                @if (auth()->user()->can('all_expense.access'))
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                                                            {!! Form::select('location_id', $business_locations, null, [
                                                                'class' => 'form-control select2',
                                                                'style' => 'width:100%',
                                                            ]) !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            {!! Form::label('expense_for', __('expense.expense_for') . ':') !!}
                                                            {!! Form::select('expense_for', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            {!! Form::label('expense_contact_filter', __('contact.contact') . ':') !!}
                                                            {!! Form::select('expense_contact_filter', $contacts, null, [
                                                                'class' => 'form-control select2',
                                                                'style' => 'width:100%',
                                                                'placeholder' => __('lang_v1.all'),
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {!! Form::label('expense_category_id', __('expense.expense_category') . ':') !!}
                                                        {!! Form::select('expense_category_id', $categories, null, [
                                                            'placeholder' => __('report.all'),
                                                            'class' => 'form-control select2',
                                                            'style' => 'width:100%',
                                                            'id' => 'expense_category_id',
                                                        ]) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {!! Form::label('expense_sub_category_id_filter', __('product.sub_category') . ':') !!}
                                                        {!! Form::select('expense_sub_category_id_filter', $sub_categories, null, [
                                                            'placeholder' => __('report.all'),
                                                            'class' => 'form-control select2',
                                                            'style' => 'width:100%',
                                                            'id' => 'expense_sub_category_id_filter',
                                                        ]) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {!! Form::label('expense_date_range', __('report.date_range') . ':') !!}
                                                        {!! Form::text('date_range', null, [
                                                            'placeholder' => __('lang_v1.select_a_date_range'),
                                                            'class' => 'form-control',
                                                            'id' => 'expense_date_range',
                                                            'readonly',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {!! Form::label('expense_payment_status', __('purchase.payment_status') . ':') !!}
                                                        {!! Form::select(
                                                            'expense_payment_status',
                                                            ['paid' => __('lang_v1.paid'), 'due' => __('lang_v1.due'), 'partial' => __('lang_v1.partial')],
                                                            null,
                                                            ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')],
                                                        ) !!}
                                                    </div>
                                                </div>
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @component('components.widget', ['class' => 'box-primary', 'title' => __('expense.all_expenses')])
                                                @can('expense.add')
                                                    <div class="table-header">
                                                        <div class="box-tools">
                                                            <a class="btn btn-block btn-primary"
                                                                href="{{ action([\App\Http\Controllers\ExpenseController::class, 'create']) }}">
                                                                <i class="fa fa-plus"></i> @lang('messages.add')</a>
                                                        </div>
                                                    </div>
                                                @endcan
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped" id="expense_table">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('messages.action')</th>
                                                                <th>@lang('messages.date')</th>
                                                                <th>@lang('purchase.ref_no')</th>
                                                                <th>@lang('lang_v1.recur_details')</th>
                                                                <th>@lang('expense.expense_category')</th>
                                                                <th>@lang('product.sub_category')</th>
                                                                <th>@lang('business.location')</th>
                                                                <th>@lang('sale.payment_status')</th>
                                                                <th>@lang('product.tax')</th>
                                                                <th>@lang('sale.total_amount')</th>
                                                                <th>@lang('purchase.payment_due')
                                                                <th>@lang('expense.expense_for')</th>
                                                                <th>@lang('contact.contact')</th>
                                                                <th>@lang('expense.expense_note')</th>
                                                                <th>@lang('lang_v1.added_by')</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr class="bg-gray font-17 text-center footer-total">
                                                                <td colspan="7"><strong>@lang('sale.total'):</strong></td>
                                                                <td class="footer_payment_status_count"></td>
                                                                <td></td>
                                                                <td class="footer_expense_total"></td>
                                                                <td class="footer_total_due"></td>
                                                                <td colspan="4"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            @endcomponent
                                        </div>
                                    </div>

                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop
@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
