@extends('layouts.app')

@section('title', __('essentials::lang.training'))

@section('content')
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
                                <li class="breadcrumb-item"><a href="{{ url('training') }}">@lang('essentials::lang.training')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>{{ $training->title }}</span>
                                </li>

                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow  box-solid">
                    <div class="widget-header">

                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>{{ $training->title }}</h4>
                            </div>
                        </div>


                    </div>

                    <div class="widget-content widget-content-area" style="width:97%; margin-left: 9px;">

                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>{!! $training->content !!}</h4>
                            </div>
                        </div>
                        <div class="row">
                            @if (!empty($training->attachment) && $training->attachment != null)

                                @foreach ($training->attachment as $document)
                                    @php $extension = pathinfo($document['dataURL'], PATHINFO_EXTENSION); @endphp
                                    @if (str_contains('.MP4,.mp4,.OGG,.ogg,.MOV,.mov,.AVI, .avi', $extension))
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <video width="400" controls>
                                                <source src="{{ $document['dataURL'] }}" type="video/{{ $extension }}">
                                            </video>
                                        </div>
                                    @else
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <label for="file-upload" class="custom-file-upload mb-0">
                                                <a href="{{ $document['dataURL'] }}" target="_blank"
                                                    title="{{ $document['name'] }}" class="mr-2 pointer text-primary">
                                                    <i class="las la-paperclip font-14"></i>
                                                    <span class="font-14">{{ $document['name'] }}</span>
                                                </a>
                                            </label>
                                            {{-- <h6>{{ $document['name'] }} </h6> --}}
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

        </div>
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
