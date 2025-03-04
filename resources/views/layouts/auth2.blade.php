<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title') - {{ config('app.name', 'POS') }} </title>
    <!-- initiate head with meta tags, css and script -->
    @include('include.head')

</head>

<body class="mode" data-base-url="{{ url('/') }}">


    <!--  Main Container Starts  -->
    @yield('content')
    <!-- Main Container Ends -->

    @stack('plugin-scripts')
</body>

</html>
