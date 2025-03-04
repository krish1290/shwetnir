<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/faviconnn.ico') }}" />

<!-- fonts library -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap"
    rel="stylesheet">

<script src="{{ asset('assets/js/app.js') }}"></script>

<link rel="stylesheet" href="{{ asset('assets/css/all.css') }}">
<link rel="stylesheet" href="{{ asset('css/app.css?v=' . $asset_v) }}">
<link rel="stylesheet" href="{{ asset('css/vendor.css?v=' . $asset_v) }}">

<link rel="stylesheet" href="{{ asset('plugins/line-awesome-1.3.0/css/line-awesome.min.css') }}">

<!-- Stack array for including inline css or head elements -->
{!! Html::style('plugins/table/datatable/datatables.css') !!}
{!! Html::style('plugins/table/datatable/dt-global_style.css') !!}
@stack('plugin-styles')
