@extends('layouts.app')
@section('title', __('sale.list_pos'))

@section('content')

    <!-- Content Header (Page header) -->

    <div class="sub-header-container  no-print" style="margin-bottom:30px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('sells') }}">@lang('sale.sells')</a></li>

                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('sale.pos_sale')</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <section class="content no-print">
        @component('components.filters', ['title' => __('report.filters')])
            @include('sell.partials.sell_list_filters')
        @endcomponent

        @component('components.widget', ['class' => 'box-primary date-table-container', 'title' => __('sale.list_pos')])
            @can('sell.create')
                <div class="table-header">
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary"
                            href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')</a>
                    </div>
                </div>
            @endcan
            @can('sell.view')
                <input type="hidden" name="is_direct_sale" id="is_direct_sale" value="0">
                @include('sale_pos.partials.sales_table')
            @endcan
        @endcomponent
    </section>
    <!-- /.content -->
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade register_details_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade close_register_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <!-- This will be printed -->
    <section class="invoice print_section" id="receipt_section">
    </section>


@stop

@section('javascript')
    @include('sale_pos.partials.sale_table_javascript')
    
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection
