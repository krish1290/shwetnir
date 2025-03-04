@extends('layouts.app')
@section('title', __('essentials::lang.essentials_n_hrm_settings'))

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
                                <li class="breadcrumb-item"><a href="{{ url('hrm/dashboard') }}">@lang('essentials::lang.hrm')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>@lang('essentials::lang.essentials_n_hrm_settings')</span>
                                </li>

                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    @include('essentials::layouts.nav_hrm')

    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content" style="margin-right:30px;">
        {!! Form::open([
            'action' => '\Modules\Essentials\Http\Controllers\EssentialsSettingsController@update',
            'method' => 'post',
            'id' => 'essentials_settings_form',
        ]) !!}
        <div class="row">
            <div class="col-xs-12">
                <!--  <pos-tab-container> -->
                <div class="col-xs-12 pos-tab-container">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                        <div class="list-group">
                            <a href="#" class="list-group-item text-center active">@lang('essentials::lang.leave')</a>
                            <a href="#" class="list-group-item text-center">@lang('essentials::lang.payroll')</a>
                            <a href="#" class="list-group-item text-center">@lang('essentials::lang.attendance')</a>
                            <a href="#" class="list-group-item text-center">@lang('essentials::lang.sales_target')</a>
                            <a href="#" class="list-group-item text-center">@lang('essentials::lang.essentials')</a>

                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('essentials::settings.partials.leave_settings')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('essentials::settings.partials.payroll_settings')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('essentials::settings.partials.attendance_settings')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('essentials::settings.partials.sales_target_settings')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                        @include('essentials::settings.partials.essentials_settings')
                    </div>
                </div>

                <!--  </pos-tab-container> -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group pull-right">
                    {{ Form::submit(__('messages.update'), ['class' => 'btn btn-danger']) }}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea#leave_instructions',
            });

            $('#essentials_settings_form').validate({
                ignore: [],
            });
        });
    </script>
@endsection
