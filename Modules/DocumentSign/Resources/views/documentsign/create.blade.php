@extends('layouts.app')
@push('plugin-styles')
{!! Html::style('assets/css/forms/file-upload.css') !!}
{!! Html::style('plugins/dropzone/dropzone.min.css') !!}
@endpush
@php
$page_title = __('documentsign::lang.add_document');

@endphp
@section('title', $page_title)

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
                <li class="breadcrumb-item active" aria-current="page"><span>{{ $page_title }}</span>
                </li>

              </ol>
            </nav>
          </div>
        </li>
      </ul>
    </header>
  </div>

  <!-- Main content -->
  <section class="content">
    @component('components.widget')
    <div class="row">
      {!! Form::open([
        'url' => action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'store']),
        'method' => 'post',
        'id' => 'documentForm',
        'files' => true,
        ]) !!}

        <div class="col-12 col-md-12">
          <div class="form-group">
            <div class="control-group" id="fields">
              <label class="control-label" for="field1">
                Select Documents:
              </label>
              <div class="controls" style="margin-left:-15px;">
                <div class="entry input-group upload-input-group" style="margin-top:5px;">
                  <div class="col-md-11">
                    <input class="form-control" name="document[]" type="file" accept=".pdf,.doc,.docx" id="pdf_files">
                  </div>
                  <div class="col-md-1">
                    <button class="btn btn-upload btn-success btn-add" type="button">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="form-group">
            {!! Form::label('location_id', __('purchase.business_location') . ':*') !!}
            {!! Form::select('location_id', $business_locations, null, [
            'class' => 'form-control select2',
            'placeholder' => __('messages.please_select'),
            'required',
            ]) !!}
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('title', __('essentials::lang.title') . ':*') !!}
            {!! Form::text('title', null, [
            'class' => 'form-control',
            'placeholder' => __('essentials::lang.title'),
            'required',
            ]) !!}
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('description', __('essentials::lang.description') . ':') !!}
            {!! Form::textarea('description', null, [
            'class' => 'form-control',
            'id' => 'description',
            'placeholder' => __('essentials::lang.description'),
            ]) !!}
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
          <input type="checkbox" id="signer" name="signer" />
      <label for="scales">I'm the only signer</label>
        </div>
      </div>
        <div class="col-md-12" id="rec">
          <label for="receipt">Receipts:</label>
          <div id="receipt-sec">
            <div class="receipt-sec row">
              <div class="col-md-3">
                <div class="form-group">
                  {!! Form::text('receipt[0][user_id]', null, [
                  'class' => 'form-control',
                  'placeholder' => __('business.user_name'),
                  'required',
                  'id' => 'user-0',
                  ]) !!}
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  {!! Form::text('receipt[0][email]', null, [
                  'class' => 'form-control',
                  'placeholder' => __('business.email'),
                  'required',
                  'id' => 'email-0',
                  ]) !!}
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  {!! Form::input('number', 'receipt[0][sequence]', null, [
                  'class' => 'form-control',
                  'placeholder' => __('Sequence'),
                  'required',
                  ]) !!}
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select name="receipt[0][type]" class="form-control" id="type-0">
                    <option value="need_to_sign">
                      Need to sign
                    </option>
                    <option value="need_to_view">
                    Need to view
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-md-1">
                <div class="form-group">
                  <button type="button" class="btn btn-primary add_receipt">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

        <div class="col-md-12">
          <div class="form-group">
            <small>
              <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                <br />Allowed File: .pdf,.doc,.docx
              </p>
            </small>
          </div>
        </div>
      </div>

        <div class="col-md-12">
          <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
        </div>
        {!! Form::close() !!}

      </div>
      @endcomponent


      @stop

      @section('javascript')
      <script id="blockOfReceipt" type="text/html">
        <div class="receipt-sec row">
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::text('receipt[{count}][user_id]', null, [
              'class' => 'form-control',
              'placeholder' => __('business.user_name'),
              'required',
              'id'=>'user-{count}'
              ]) !!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::text('receipt[{count}][email]', null, [
              'class' => 'form-control',
              'placeholder' => __('business.email'),
              'required',
              'id'=>'email-{count}'
              ]) !!}
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              {!! Form::input('number', 'receipt[{count}][sequence]', null, [
              'class' => 'form-control',
              'placeholder' => __('Sequence'),
              'required',
              ]) !!}
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <select name="receipt[{count}][type]" class="form-control" id="type-0">
                <option value="need_to_sign">
                  Need to sign
                </option>
                <option value="need_to_view">
                Need to view
                </option>
              </select>
            </div>
          </div>
          <div class="col-md-1">
            <div class="form-group">
              <button type="button" class="btn btn-danger remove_receipt">
                <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
        </div>
      </script>
      <script type="text/javascript">
      function GetReceiptBlock(value = "") {
        innerHTML = document.getElementById('blockOfReceipt').innerHTML;
        innerHTML = innerHTML.replaceAll("{count}", value);

        return innerHTML;
      }
      var counter = 0;
      $(document).ready(function() {
        $(".add_receipt").bind("click", function() {
          counter++;
          $("#receipt-sec").append(GetReceiptBlock(counter));
        });
        $("body").on("click", ".remove_receipt", function() {
          $(this).closest(".row").remove();
        });
        $("body").on("change", ".user-select", function() {
          var email = $(this).find(':selected').data("email");
          var count = $(this).find(':selected').data("count");
          var user_id = $(this).val();
          var email_val = $("#email-" + count).val(email);

        });

      });
      $(document).on("click",'.btn-add', function(e) {
        e.preventDefault();

        var controlForm = $('.controls:first'),
        currentEntry = $(this).parents('.entry:first'),
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry:not(:last) .btn-add')
        .removeClass('btn-add').addClass('btn-remove')
        .removeClass('btn-success').addClass('btn-danger')
        .html('<span class="fa fa-trash"></span>');
      }).on('click', '.btn-remove', function (e) {
        $(this).parents('.entry:first').remove();

        e.preventDefault();
        return false;
      });
      $(document).ready(function(){
    $('#signer').change(function(){
        if(this.checked) {
            $('#rec').hide();
            $('#receipt-sec input, #receipt-sec select').removeAttr('required');
        } else {
            $('#rec').show();
            $('#receipt-sec input, #receipt-sec select').attr('required', true);
        }
    });
});
$(document).ready(function() {
    $('#pdf_files').change(function() {
        var files = $(this)[0].files;

        // Iterate through each file
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var ext = file.name.split('.').pop().toLowerCase();

            // Check if file extension is valid
            if (ext !== 'pdf' && ext !== 'doc' && ext !== 'docx') {
                alert('Please select PDF or Word documents only.');
                $('#pdf_files').val(''); // Clear the file input
                return false; // Exit function
            }
        }
    });
});
      </script>
      @endsection
