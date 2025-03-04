@extends('layouts.app')
@section('title', __('messages.settings'))

@section('content')
@include('manufacturing::layouts.nav')
<!-- Content Header (Page header) -->
<div class="card bg-white p-6 rounded-xl shadow-xl m-4 p-4" style="box-shadow: 1px 1px 1px 4px #f7f4f4">
    <section class="content-header m-2">
        <h3 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('messages.settings')</h3>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open(['url' => action([\Modules\Manufacturing\Http\Controllers\SettingsController::class, 'store']), 'method' => 'post', 'id' => 'manufacturing_settings_form' ]) !!}
        <div class="row">
            <div class="col-xs-12">
            <!--  <pos-tab-container> -->
                {{-- <div class="col-xs-12 pos-tab-container"> --}}
                    @component('components.widget', ['class' =>  'pos-tab-container'])
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu tw-rounded-lg">
                        <div class="list-group">
                            <a href="#" class="list-group-item text-center tw-font-bold tw-text-sm md:tw-text-base active">@lang('messages.settings')</a>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        <div class="pos-tab-content active">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('ref_no_prefix', __('manufacturing::lang.mfg_ref_no_prefix') . ':' ) !!}
                                        {!! Form::text('ref_no_prefix', !empty($manufacturing_settings['ref_no_prefix']) ? $manufacturing_settings['ref_no_prefix'] : null, ['placeholder' => __('manufacturing::lang.mfg_ref_no_prefix'), 'class' => 'form-control']); !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::checkbox('disable_editing_ingredient_qty', 1, !empty($manufacturing_settings['disable_editing_ingredient_qty']), ['class' => 'input-icheck', 'id' => 'disable_editing_ingredient_qty']); !!} @lang('manufacturing::lang.disable_editing_ingredient_qty')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <div class="checkbox">
                                            <label>
                                            {!! Form::checkbox('enable_updating_product_price', 1, !empty($manufacturing_settings['enable_updating_product_price']), ['class' => 'input-icheck', 'id' => 'enable_updating_product_price']); !!} @lang('manufacturing::lang.enable_editing_product_price_after_production')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    @endcomponent
                    
                {{-- </div> --}}
                <!--  </pos-tab-container> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white pull-right">@lang('messages.update')</button>
            </div>
        </div>

        <div class="col-xs-12">
            <p class="help-block"><i>{!! __('manufacturing::lang.version_info', ['version' => $version]) !!}</i></p>
        </div>
        {!! Form::close() !!}
    </section>
</div>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready( function () {
        $(".file-input").fileinput(fileinput_setting);
    });
</script>

@endsection