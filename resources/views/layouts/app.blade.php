@inject('request', 'Illuminate\Http\Request')
@if (
    $request->segment(1) == 'pos' &&
        ($request->segment(2) == 'create' || $request->segment(3) == 'edit' || $request->segment(2) == 'payment' || $request->segment(2) == 'duplicate-sale'))
    @php
        $pos_layout = true;
    @endphp
@else
    @php
        $pos_layout = false;
    @endphp
@endif

@php
    $whitelist = ['127.0.0.1', '::1'];
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
    dir="{{ in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ Session::get('business.name') }}</title>
    @include('include.head')

</head>

<body class="{{ $theme . 'mode' }}" data-base-url="{{ url('/') }}">


    <!--  Navbar Starts  -->
    @if (!$pos_layout)
        <div class="header-container fixed-top">
            @include('include.header')
        </div>
    @else
        @include('layouts.partials.header-pos')
    @endif


    <!--  Navbar Ends  -->
    <!--  Main Container Starts  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>
        <div class="rightbar-overlay"></div>
        <section class="invoice print_section" id="receipt_section">
        </section>
        @if (!$pos_layout)
            <!--  Sidebar Starts  -->
            <div class="sidebar-wrapper sidebar-theme no-print">
                @include('include.sidebar')
            </div>
            <!--  Sidebar Ends  -->
        @endif

        <!--  Content Area Starts  -->
        <div id="content" class="main-content no-print"
            @if ($pos_layout) style="margin-left:0px;margin-top:20px;" @endif>
            <!-- Add currency related field-->
            <input type="hidden" id="__code" value="{{ session('currency')['code'] }}">
            <input type="hidden" id="__symbol" value="{{ session('currency')['symbol'] }}">
            <input type="hidden" id="__thousand" value="{{ session('currency')['thousand_separator'] }}">
            <input type="hidden" id="__decimal" value="{{ session('currency')['decimal_separator'] }}">
            <input type="hidden" id="__symbol_placement" value="{{ session('business.currency_symbol_placement') }}">
            <input type="hidden" id="__precision" value="{{ session('business.currency_precision', 2) }}">
            <input type="hidden" id="__quantity_precision" value="{{ session('business.quantity_precision', 2) }}">
            <!-- End of currency related field-->
            <!-- Main Body Starts -->
            @yield('content')
            <!-- Main Body Ends -->

            @include('include.responsive-message')
            @if (!$pos_layout)
                @include('include.footer')
            @else
                @include('layouts.partials.footer_pos')
            @endif


            <!-- Arrow Starts -->
            <div class="scroll-top-arrow" style="display: none;">
                <i class="las la-angle-up"></i>
            </div>
            <!-- Arrow Ends -->
        </div>
        <!--  Content Area Ends  -->
        @include('home.todays_profit_modal')
    </div>
    <!-- Main Container Ends -->

    <!-- Common Script Starts -->
    @include('include.scripts')
    <!-- Common Script Ends -->


    <div class="modal fade view_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

    @if (!empty($__additional_views) && is_array($__additional_views))
        @foreach ($__additional_views as $additional_view)
            @includeIf($additional_view)
        @endforeach
    @endif

</body>

</html>
