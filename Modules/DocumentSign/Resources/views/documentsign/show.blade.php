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
                                <li class="breadcrumb-item"><a
                                        href="{{ url('documentsign/document') }}">@lang('documentsign::lang.documents')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>{{ $document->title }}</span>
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
                                <h4>{{ $document->title }}</h4>
                            </div>
                        </div>


                    </div>

                    <div class="widget-content widget-content-area" style="width:97%; margin-left: 9px;">

                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>{!! $document->description !!}</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <label for="file-upload" class="custom-file-upload mb-0">
                                  <!-- <iframe src="{{ asset('uploads/merged_documents/' . $document->document) }}" width="100%" height="800px"></iframe> -->
                                  <a href="{{ asset('uploads/merged_documents/' . $document->document) }}" target="_blank"
                                      title="{{ $document['title'] }}" class="mr-2 pointer text-primary">
                                      <i class="las la-paperclip font-14"></i>
                                      <span class="font-14">{{ $document['document'] }}</span>
                                  </a>
                                  <!-- @foreach($document->documents as $key=>$val)
                                    <a href="{{ asset('uploads/documents/' . $val->document) }}" target="_blank"
                                        title="{{ $document['title'] }}" class="mr-2 pointer text-primary">
                                        <i class="las la-paperclip font-14"></i>
                                        <span class="font-14">{{ $document['document'] }}</span>
                                    </a>
                                    @endforeach -->
                                </label>
                                {{-- <h6>{{ $document['name'] }} </h6> --}}
                            </div>

                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4> Activity Log</h4>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="training_table">
                                <thead>
                                    <tr>
                                        <th>@lang('business.email')</th>
                                        <th>{{ __('Sequence') }}</th>
                                        <th>{{ __('Send At') }}</th>
                                        <th>{{ __('Signed At') }}</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                    @foreach ($document->receipters as $receipt)
                                        <tr>
                                            <td>{{ $receipt->email }}</td>
                                            <td>{{ $receipt->sequence }}</td>
                                            <td>{{ @format_date($receipt->created_at) }}</td>
                                            <td>
                                                @if ($receipt->signed_at)
                                                    <span class="badge badge-success">
                                                        {{ @format_date($receipt->signed_at) }}</span>
                                                @else
                                                    <span class="badge badge-warning">Not Yet</span>
                                                @endif
                                            </td>
                                            <td>@lang('messages.action')</td>
                                        </tr>
                                    @endforeach
                                </thead>
                            </table>
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
