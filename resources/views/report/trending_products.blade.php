@extends('layouts.app')
@section('title', __('report.trending_products'))

@section('content')

    <!-- Content Header (Page header) -->
    <div class="sub-header-container  no-print"style="margin-bottom:30px;">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <i class="las la-bars"></i>
            </a>
            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">
                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('report.trending_products')</a></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row no-print">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    {!! Form::open([
                        'url' => action([\App\Http\Controllers\ReportController::class, 'getTrendingProducts']),
                        'method' => 'get',
                    ]) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                            {!! Form::select('location_id', $business_locations, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('category_id', __('product.category') . ':') !!}
                            {!! Form::select('category', $categories, null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id' => 'category_id',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                            {!! Form::select('sub_category', [], null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id' => 'sub_category_id',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('brand', __('product.brand') . ':') !!}
                            {!! Form::select('brand', $brands, null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('unit', __('product.unit') . ':') !!}
                            {!! Form::select('unit', $units, null, [
                                'placeholder' => __('messages.all'),
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('trending_product_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'trending_product_date_range',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('limit', __('lang_v1.no_of_products') . ':') !!} @show_tooltip(__('tooltip.no_of_products_for_trending_products'))
                            {!! Form::number('limit', 5, [
                                'placeholder' => __('lang_v1.no_of_products'),
                                'class' => 'form-control',
                                'min' => 1,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('product_type', __('product.product_type') . ':') !!}
                            {!! Form::select(
                                'product_type',
                                ['single' => __('lang_v1.single'), 'variable' => __('lang_v1.variable'), 'combo' => __('lang_v1.combo')],
                                request()->input('product_type'),
                                ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%'],
                            ) !!}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right">@lang('report.apply_filters')</button>
                    </div>
                    {!! Form::close() !!}
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @component('components.widget', ['class' => 'box-primary'])
                    @slot('title')
                        @lang('report.top_trending_products') @show_tooltip(__('tooltip.top_trending_products'))
                    @endslot
                    {!! $chart->container() !!}
                @endcomponent
            </div>
        </div>
        <div class="row no-print">
            <div class="col-sm-12">
                <button type="button" class="btn btn-primary pull-right" aria-label="Print" onclick="window.print();"><i
                        class="fa fa-print"></i> @lang('messages.print')</button>
            </div>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    {!! $chart->script() !!}
@endsection
