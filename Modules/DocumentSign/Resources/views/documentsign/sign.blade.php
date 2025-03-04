@extends('layouts.sign')

@section('title', __('documentsign::lang.sign_document'))

@section('content')
    <div class="container">
        <div class="row mt-5 pb-5">
            <div class="col-md-12 align-self-center order-md-0 order-1 mt-4">
                <h1 class="text-center mb-2">Document Sign</h1>
                <p class="text-center mb-5">{{ $document->title }}</p>
            </div>
        </div>
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
                      @foreach($document->documents as $key=>$val)
                      <a href="{{ url('uploads/documents/' . $val->document) }}" target="_blank"
                          title="{{ $document['title'] }}" class="mr-2 pointer text-primary">
                          <i class="las la-paperclip font-14"></i>
                          <span class="font-14">{{ $document['document'] }}</span>
                      </a>
                      @endforeach
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

        </div>
    </section>
@endsection
<style>
    #content {
        margin-left: 0px !important;
    }
</style>
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
