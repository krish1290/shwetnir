@extends('layouts.app')
@push('plugin-styles')
    {!! Html::style('assets/css/forms/file-upload.css') !!}
    {!! Html::style('plugins/dropzone/dropzone.min.css') !!}
@endpush
@php
    $page_title = __('documentsign::lang.edit_document');
    
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
                    'url' => action([\Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'update'], $document->id),
                    'method' => 'put',
                    'id' => 'documentForm',
                    'files' => true,
                ]) !!}
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('location_id', __('purchase.business_location') . ':*') !!}
                        {!! Form::select('location_id', $business_locations, $document->location_id, [
                            'class' => 'form-control select2',
                            'placeholder' => __('messages.please_select'),
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('title', __('essentials::lang.title') . ':*') !!}
                        {!! Form::text('title', $document->title, [
                            'class' => 'form-control',
                            'placeholder' => __('essentials::lang.title'),
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('description', __('essentials::lang.description') . ':') !!}
                        {!! Form::textarea('description', $document->description, [
                            'class' => 'form-control',
                            'id' => 'description',
                            'placeholder' => __('essentials::lang.description'),
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="receipt">Receipts:</label>
                    <div id="receipt-sec">

                        <div class="receipt-sec row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control user-select" name="receipt[0][user_id]">
                                        <option selected="selected" value="">Select a User</option>
                                        @foreach ($users as $id => $user)
                                            <option data-count="0" data-email={{ $emailusers[$id] }} value={{ $id }}>
                                                {{ $user }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::text('receipt[0][email]', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('business.email'),
                                        'id' => 'email-0',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::input('number', 'receipt[0][sequence]', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('Sequence'),
                                    ]) !!}
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
                        @if (!empty($document->receipters))
                            <?php $edit_counter = 1; ?>
                            @foreach ($document->receipters as $receipter)
                                <div class="receipt-sec row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control user-select"
                                                name="receipt[{{ $edit_counter }}][user_id]">
                                                <option selected="selected" value="">Select a User</option>
                                                @foreach ($users as $id => $user)
                                                    <option <?php echo $receipter->user_id == $id ? 'selected' : ''; ?> data-count="{{ $edit_counter }}"
                                                        data-email={{ $emailusers[$id] }} value={{ $id }}>
                                                        {{ $user }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::text('receipt[' . $edit_counter . '][email]', $receipter->email, [
                                                'class' => 'form-control',
                                                'placeholder' => __('business.email'),
                                                'required',
                                                'id' => 'email-' . $edit_counter,
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::input('number', 'receipt[' . $edit_counter . '][sequence]', $receipter->sequence, [
                                                'class' => 'form-control',
                                                'placeholder' => __('Sequence'),
                                                'required',
                                            ]) !!}
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
                                <?php $edit_counter++; ?>
                            @endforeach
                        @endif

                    </div>

                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('document', __('documentsign::lang.document') . ':') !!}
                        {!! Form::file('document', [
                            'id' => 'upload_document',
                            'accept' => '.pdf',
                        ]) !!}
                        <small>
                            <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])
                                <br />Allowed File: .pdf
                            </p>
                        </small>
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
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control user-select"  name="receipt[{count}][user_id]">
                            <option selected="selected" value="">Select a User</option>
                            @foreach($users as $id=> $user )
                                <option  data-count="{count}" data-email={{$emailusers[$id]}} value={{$id}} >{{$user}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::text('receipt[{count}][email]', null, [
                            'class' => 'form-control',
                            'placeholder' => __('business.email'),
                            'required',
                            'id'=>'email-{count}'
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::input('number', 'receipt[{count}][sequence]', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Sequence'),
                            'required',
                        ]) !!}
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

            $(document).ready(function() {
                var counter = "<?php echo $edit_counter; ?>";
                $(".add_receipt").bind("click", function() {



                    $("#receipt-sec").append(GetReceiptBlock(counter));
                    counter++;
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
        </script>
    @endsection
