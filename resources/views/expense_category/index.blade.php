@extends('layouts.app')
@section('title', __('expense.expense_categories'))

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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('expense.expense_categories')</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('expense.manage_your_expense_categories')</a></li>
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
                                    @component('components.widget', ['class' => 'box-primary', 'title' => __('expense.all_your_expense_categories')])
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-block btn-primary btn-modal"
                                                    data-href="{{ action([\App\Http\Controllers\ExpenseCategoryController::class, 'create']) }}"
                                                    data-container=".expense_category_modal">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="expense_category_table">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('expense.category_name')</th>
                                                        <th>@lang('expense.category_code')</th>
                                                        <th>@lang('messages.action')</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    @endcomponent

                                    <div class="modal fade expense_category_modal" tabindex="-1" role="dialog"
                                        aria-labelledby="gridSystemModalLabel">
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

@endsection
