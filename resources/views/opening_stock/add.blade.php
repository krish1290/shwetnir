@extends('layouts.app')
@section('title', __('lang_v1.add_opening_stock'))

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
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('lang_v1.add_opening_stock')</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>


    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\OpeningStockController::class, 'save']),
            'method' => 'post',
            'id' => 'add_opening_stock_form',
        ]) !!}
        {!! Form::hidden('product_id', $product->id) !!}
        @include('opening_stock.form-part')
        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
            </div>
        </div>

        {!! Form::close() !!}
    </section>
@stop
@section('javascript')
    <script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.os_date').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
                sideBySide: true,
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'bottom'
                }
            });
        });
    </script>
@endsection
