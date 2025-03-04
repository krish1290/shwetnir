@extends('layouts.app')
@section('title', __('lang_v1.my_profile'))

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('lang_v1.my_profile')</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\UserController::class, 'updatePassword']),
            'method' => 'post',
            'id' => 'edit_password_form',
            'class' => 'form-horizontal',
        ]) !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <!--business info box start-->
                    <div class="box-header">
                        <div class="box-header">
                            <h3 class="box-title"> @lang('user.change_password')</h3>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('current_password', __('user.current_password') . ':', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    {!! Form::password('current_password', [
                                        'class' => 'form-control',
                                        'placeholder' => __('user.current_password'),
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('new_password', __('user.new_password') . ':', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    {!! Form::password('new_password', [
                                        'class' => 'form-control',
                                        'placeholder' => __('user.new_password'),
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('confirm_password', __('user.confirm_new_password') . ':', [
                                'class' => 'col-sm-3 control-label',
                            ]) !!}
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    {!! Form::password('confirm_password', [
                                        'class' => 'form-control',
                                        'placeholder' => __('user.confirm_new_password'),
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        {!! Form::open([
            'url' => action([\App\Http\Controllers\UserController::class, 'updateProfile']),
            'method' => 'post',
            'id' => 'edit_user_profile_form',
            'files' => true,
        ]) !!}
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-solid">
                    <!--business info box start-->
                    <div class="box-header">
                        <div class="box-header">
                            <h3 class="box-title"> @lang('user.edit_profile')</h3>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-2">
                            {!! Form::label('surname', __('business.prefix') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::text('surname', $user->surname, [
                                    'class' => 'form-control',
                                    'placeholder' => __('business.prefix_placeholder'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            {!! Form::label('first_name', __('business.first_name') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::text('first_name', $user->first_name, [
                                    'class' => 'form-control',
                                    'placeholder' => __('business.first_name'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            {!! Form::label('last_name', __('business.last_name') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::text('last_name', $user->last_name, [
                                    'class' => 'form-control',
                                    'placeholder' => __('business.last_name'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('email', __('business.email') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::email('email', $user->email, ['class' => 'form-control', 'placeholder' => __('business.email')]) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('language', __('business.language') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-info"></i>
                                </span>
                                {!! Form::select('language', $languages, $user->language, ['class' => 'form-control select2']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @component('components.widget', ['title' => __('lang_v1.profile_photo')])
                    @if (!empty($user->media))
                        <div class="col-md-12 text-center">
                            {!! $user->media->thumbnail([150, 150], 'img-circle') !!}
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('profile_photo', __('lang_v1.upload_image') . ':') !!}
                            {!! Form::file('profile_photo', ['id' => 'profile_photo', 'accept' => 'image/*']) !!}
                            <small>
                                <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000])</p>
                            </small>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        @include('user.edit_profile_form_part', [
            'bank_details' => !empty($user->bank_details) ? json_decode($user->bank_details, true) : null,
        ])
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
          <div class="widget-content widget-content-area br-6">
            <div class="table-header">
              <h4>@lang('user.user_document')</h4>
            </div>
            @can('user.view')
            <div class="table-responsive mb-4">
              <table id="table_new" class="table table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th>@lang('user.doc_name')</th>
                    <th>@lang('user.doc_note')</th>
                    <th>@lang('messages.action')</th>
                  </tr>
                </thead>

                <tfoot>
                  <tr>
                    <th>@lang('user.doc_name')</th>
                    <th>@lang('user.doc_note')</th>
                    <th>@lang('messages.action')</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            @endcan
          </div>
        </div>

      </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-big">@lang('messages.update')</button>
            </div>
        </div>
        {!! Form::close() !!}

    </section>
    <!-- /.content -->
@endsection
@push('custom-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
  @if(Session::has('message'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.warning("{{ session('warning') }}");
  @endif
</script>
    <script>
        $(document).ready(function() {

            $("#copy_address").click(function() {
                let permanent_address = $("#permanent_address").val();
                if ($(this).is(":checked")) {

                    $("#current_address").val(permanent_address);

                } else {

                    $("#current_address").val('');

                }
            });
            var id = "{{$user->id}}";
            $('#table_new').DataTable({
                      processing: true,
                      serverSide: true,
                      ajax: "{{ url('user/docList') }}"+"/"+id, // Specify the route for server-side processing
                      columns: [
                          { data: 'doc_name', name: 'doc_name' },
                          { data: 'doc_note', name: 'doc_note' },
                          { data: 'action', name: 'action' },
                          // Add other columns as needed
                      ]
                  });

        });
    </script>
@endpush
