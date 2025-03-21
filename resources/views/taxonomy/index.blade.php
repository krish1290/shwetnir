@extends('layouts.app')
@php
    $heading = !empty($module_category_data['heading']) ? $module_category_data['heading'] : __('category.categories');
    $navbar = !empty($module_category_data['navbar']) ? $module_category_data['navbar'] : null;
@endphp
@section('title', $heading)

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
                                <li class="breadcrumb-item"><a href="{{ url('products') }}">@lang('sale.products')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span><small>
                                            {{ $module_category_data['sub_heading'] ?? __('category.manage_your_categories') }}
                                        </small>
                                        @if (isset($module_category_data['heading_tooltip']))
                                            @show_tooltip($module_category_data['heading_tooltip'])
                                        @endif
                                    </span></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    @if (!empty($navbar))
        @include($navbar)
    @endif

    <!-- Main content -->
    <div class="layout-px-spacing  no-print">
        <div class="layout-top-spacing mb-2">
            <div class="col-md-12 p-0">
                <div class="row">
                    <div class="container p-0 m-0">
                        <div class="row layout-top-spacing date-table-container">

                            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                                @php
                                    $cat_code_enabled = isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code'] ? false : true;
                                @endphp
                                <input type="hidden" id="category_type" value="{{ request()->get('type') }}">
                                @php
                                    $can_add = true;
                                    if (
                                        request()->get('type') == 'product' &&
                                        !auth()
                                            ->user()
                                            ->can('category.create')
                                    ) {
                                        $can_add = false;
                                    }
                                @endphp
                                @component('components.widget', ['class' => 'box-solid', 'can_add' => $can_add])
                                    @if ($can_add)
                                        <div class="table-header">
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-block btn-primary btn-modal"
                                                    data-href="{{ action([\App\Http\Controllers\TaxonomyController::class, 'create']) }}?type={{ request()->get('type') }}"
                                                    data-container=".category_modal">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</button>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="category_table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        @if (!empty($module_category_data['taxonomy_label']))
                                                            {{ $module_category_data['taxonomy_label'] }}
                                                        @else
                                                            @lang('category.category')
                                                        @endif
                                                    </th>
                                                    @if ($cat_code_enabled)
                                                        <th>{{ $module_category_data['taxonomy_code_label'] ?? __('category.code') }}
                                                        </th>
                                                    @endif
                                                    <th>@lang('lang_v1.description')</th>
                                                    <th>@lang('messages.action')</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                @endcomponent

                                <div class="modal fade category_modal" tabindex="-1" role="dialog"
                                    aria-labelledby="gridSystemModalLabel">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@stop
@section('javascript')
    @includeIf('taxonomy.taxonomies_js')
@endsection
