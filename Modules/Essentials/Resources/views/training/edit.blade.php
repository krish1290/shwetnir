@extends('layouts.app')
@push('plugin-styles')
    {!! Html::style('assets/css/forms/file-upload.css') !!}
    {!! Html::style('plugins/dropzone/dropzone.min.css') !!}
@endpush
@php
    $page_title = __('essentials::lang.edit_training');
    
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
                                <li class="breadcrumb-item"><a href="{{ url('training') }}">@lang('essentials::lang.training')</a></li>
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
                    'url' => action([\Modules\Essentials\Http\Controllers\TrainingController::class, 'update'], [$training->id]),
                    'method' => 'put',
                    'id' => 'trainingForm',
                ]) !!}
                {!! Form::hidden('uploaded_docs', null, ['id' => 'uploaded_docs']) !!}
                {!! Form::hidden('deleted_docs', null, ['id' => 'deleted_docs']) !!}
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('title', __('essentials::lang.title') . ':*') !!}
                        {!! Form::text('title', $training->title, [
                            'class' => 'form-control',
                            'placeholder' => __('essentials::lang.title'),
                            'required',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('content', __('essentials::lang.content') . ':') !!}
                        {!! Form::textarea('content', $training->content, [
                            'class' => 'form-control',
                            'id' => 'training_content',
                            'placeholder' => __('essentials::lang.content'),
                        ]) !!}

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('role_id', __('essentials::lang.share_with') . ':') !!}
                        {!! Form::select('role_id', $roles, $training->role_id, [
                            'class' => 'form-control ',
                            'placeholder' => 'Select a Role',
                        ]) !!}
                    </div>
                </div>



                <div class="col-md-12">
                    <div id="dropzone" class="form-group">
                        {!! Form::label('attachment', __('essentials::lang.attachment') . ':') !!}
                        <div action="/upload" class="dropzone-div needsclick dz-clickable" id="demo-upload">
                            <div class="dz-message needsclick">
                                <button type="button" class="dz-button">{{ __('essentials::lang.attachment_text') }}</button>
                                <br>
                                <span class="note needsclick">{{ __('essentials::lang.attachment_note') }}</span>
                            </div>
                        </div>
                        <div class="invalid-feedback" style="display: block;" id="file-error">

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
    @push('plugin-scripts')
        {!! Html::script('plugins/dropzone/dropzone.min.js') !!}
    @endpush
    @section('javascript')
        <script type="text/javascript">
            $(document).ready(function() {
                $("#file-error").text("");
                tinymce.init({
                    selector: 'textarea#training_content',
                });


                $('#share_with').change(function() {
                    if ($(this).val() == 'only_with') {
                        $('#user_ids_div').fadeIn();
                    } else {
                        $('#user_ids_div').fadeOut();
                    }
                });
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                let mockFile = "{{ json_encode($training->attachment) }}";
                let editFiles = [];
                if (mockFile !== 'null') {
                    editFiles = JSON.parse(mockFile.replace(/&quot;/g, '"'));
                }
                $('#uploaded_docs').val(JSON.stringify(editFiles));
                let deletedFile = [];
                let uploadedFile = [];
                var myDropzone = new Dropzone("div#demo-upload", {
                    url: '{{ env('APP_URL') }}/training/uploadDocs',
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    success: function(file, response) {
                        if (response.success == 0) {
                            $("#file-error").text(response.error);
                            return false;
                        }
                        $("#file-error").text("");
                        editFiles.push({
                            name: file.name,
                            id: file.upload.uuid,
                            path: response.filepath,
                            dataURL: response.url,
                            size: response.size
                        });
                        if ($('#uploaded_docs').val()) {
                            uploadedFile = $('#uploaded_docs').val();
                            uploadedFile = (uploadedFile !== 'null') ? JSON.parse(uploadedFile) : [];
                        }

                        uploadedFile.push({
                            name: file.name,
                            id: file.upload.uuid,
                            path: response.filepath,
                            dataURL: response.url,
                            size: response.size
                        });
                        $('#uploaded_docs').val(JSON.stringify(uploadedFile));

                    },
                    error: function(file, response) {
                        return false;
                    },
                    addRemoveLinks: true,
                    init: function() {
                        //show existing file
                        for ($i = 0; $i < editFiles.length; $i++) {
                            this.displayExistingFile(editFiles[$i],
                                'https://cdn-icons-png.flaticon.com/512/2991/2991106.png');
                            this.files.push(editFiles[$i]);
                        }
                        this.on("removedfile", function(file) {
                            $("#file-error").text("");
                            let arrFile = [];
                            if (file?.upload !== undefined) { //delete uploded file
                                arrFile = editFiles.filter((item) => {
                                    return item.id === file.upload.uuid
                                })[0];

                                if ($('#uploaded_docs').val()) {
                                    uploadedFile = JSON.parse($('#uploaded_docs').val());
                                }
                                uploadedFile = uploadedFile.filter((item) => {
                                    return item.id !== file.upload.uuid
                                })
                                $('#uploaded_docs').val(JSON.stringify(uploadedFile));

                            } else { //delete existing file
                                arrFile = editFiles.filter((item) => {
                                    return item.id === file.id
                                })[0];
                                deletedFile = editFiles.filter((item) => {
                                    return item.id === file.id
                                })[0];
                                if ($('#uploaded_docs').val()) {
                                    uploadedFile = JSON.parse($('#uploaded_docs').val());
                                }
                                uploadedFile = uploadedFile.filter((item) => {
                                    return item.id !== file.id
                                })
                                $('#uploaded_docs').val(JSON.stringify(uploadedFile));
                            }

                            $.ajax({
                                url: '{{ env('APP_URL') }}/training/removeDocs',
                                type: "POST",
                                data: {
                                    "filepath": arrFile.path
                                },
                                headers: {
                                    'x-csrf-token': CSRF_TOKEN,
                                }

                            });

                        });
                    }
                });


            });
        </script>
    @endsection
