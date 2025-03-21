@extends('layouts.app')
@section('title', __('lang_v1.product_stock_history'))

@section('content')

    <!-- Content Header (Page header) -->

    <div class="sub-header-container  no-print" style="margin-bottom:40px;">
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
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('lang_v1.product_stock_history')</span></li>
                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['title' => $product->name])
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('product_id', __('sale.product') . ':') !!}
                            {!! Form::select('product_id', [$product->id => $product->name . ' - ' . $product->sku], $product->id, [
                                'class' => 'form-control',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                            {!! Form::select('location_id', $business_locations, request()->input('location_id', null), [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    @if ($product->type == 'variable')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="variation_id">@lang('product.variations'):</label>
                                <select class="select2 form-control" name="variation_id" id="variation_id">
                                    @foreach ($product->variations as $variation)
                                        <option value="{{ $variation->id }}" @if (request()->input('variation_id', null) == $variation->id) selected @endif>
                                            {{ $variation->product_variation->name }} - {{ $variation->name }}
                                            ({{ $variation->sub_sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" id="variation_id" name="variation_id"
                            value="{{ $product->variations->first()->id }}">
                    @endif
                @endcomponent
                @component('components.widget')
                    <div id="product_stock_history" style="display: none;"></div>
                @endcomponent
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            load_stock_history($('#variation_id').val(), $('#location_id').val());

            $('#product_id').select2({
                ajax: {
                    url: '/products/list-no-variation',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term, // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                minimumInputLength: 1,
                escapeMarkup: function(m) {
                    return m;
                },
            }).on('select2:select', function(e) {
                var data = e.params.data;
                window.location.href = "{{ url('/') }}/products/stock-history/" + data.id
            });
        });

        function load_stock_history(variation_id, location_id) {
            $('#product_stock_history').fadeOut();
            $.ajax({
                url: '/products/stock-history/' + variation_id + "?location_id=" + location_id,
                dataType: 'html',
                success: function(result) {
                    $('#product_stock_history')
                        .html(result)
                        .fadeIn();

                    __currency_convert_recursively($('#product_stock_history'));

                    $('#stock_history_table').DataTable({
                        searching: false,
                        ordering: false
                    });
                },
            });
        }

        $(document).on('change', '#variation_id, #location_id', function() {
            load_stock_history($('#variation_id').val(), $('#location_id').val());
        });
    </script>
@endsection
