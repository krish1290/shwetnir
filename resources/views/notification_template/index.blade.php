@extends('layouts.app')
@section('title', __('lang_v1.notification_templates'))

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
                                <li class="breadcrumb-item"><a
                                        href="{{ url('sells') }}">{{ __('lang_v1.notification_templates') }}</a></li>


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
            'url' => action([\App\Http\Controllers\NotificationTemplateController::class, 'store']),
            'method' => 'post',
        ]) !!}

        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.notifications') . ':'])
                    @include('notification_template.partials.tabs', [
                        'templates' => $general_notifications,
                    ])
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.customer_notifications') . ':'])
                    @include('notification_template.partials.tabs', [
                        'templates' => $customer_notifications,
                    ])
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.supplier_notifications') . ':'])
                    @include('notification_template.partials.tabs', [
                        'templates' => $supplier_notifications,
                    ])

                    <div class="callout callout-warning">
                        <p>@lang('lang_v1.logo_not_work_in_sms'):</p>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-danger btn-big">@lang('messages.save')</button>
            </div>
        </div>
        {!! Form::close() !!}

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $('textarea.ckeditor').each(function() {
            var editor_id = $(this).attr('id');
            tinymce.init({
                selector: 'textarea#' + editor_id,
            });
        });
    </script>
@endsection
