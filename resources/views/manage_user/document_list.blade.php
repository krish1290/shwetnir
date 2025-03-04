@extends('layouts.app')
@section('title', __('user.users'))
@push('plugin-styles')
{!! Html::style('plugins/table/datatable/datatables.css') !!}
{!! Html::style('plugins/table/datatable/dt-global_style.css') !!}
<!-- {!! Html::style('plugins/sweetalerts/sweetalert2.min.css') !!} -->
<!-- {!! Html::style('plugins/sweetalerts/sweetalert.css') !!} -->
<!-- {!! Html::style('assets/css/basic-ui/custom_sweetalert.css') !!} -->
@endpush
@section('content')
@php
    $urlSegments = explode('/', request()->path());
    $lastSegment = last($urlSegments);
@endphp
{{dd($lastSegment)}}
<!--  Navbar Starts / Breadcrumb Area  -->
<div class="sub-header-container">
  <header class="header navbar navbar-expand-sm">
    <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
      <i class="las la-bars"></i>
    </a>
    <ul class="navbar-nav flex-row">
      <li>
        <div class="page-header">
          <nav class="breadcrumb-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('user.user_document')</a></li>
            </ol>
          </nav>
        </div>
      </li>
    </ul>
  </header>
</div>
<!-- Main content -->
<div class="layout-px-spacing">
  <div class="layout-top-spacing mb-2">
    <div class="col-md-12">
      <div class="row">
        <div class="container p-0">
          <div class="row layout-top-spacing date-table-container">
            <!-- All Users -->
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              <div class="widget-content widget-content-area br-6">
                <div class="table-header">
                  <h4>@lang('user.user_document')</h4>
                  @can('document.create')
                  <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary" id="user_doc">
                    <i class="las la-plus"></i> @lang('messages.add_doc')</a>
                  </div>
                  @endcan
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
        </div>
      </div>
    </div>
  </div>
</div>
<!--/Main content-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document" style="width:43%;">
    <div class="modal-content">

      {!! Form::open(['url' => action([\App\Http\Controllers\ManageUserController::class, 'addDoc']), 'method' => 'post', 'id' => 'add_doc','enctype'=>'multipart/form-data' ]) !!}
      <input type="hidden" name="user_id" id="user_id">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'user.add_doc' )</h4>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('doc_name', __('user.doc_name') . ':*') !!}
              <div class='input-group date' >
                {!! Form::text('doc_name', null, ['class' => 'form-control','placeholder' => __( 'user.doc_name' ), 'id' => 'doc_name']); !!}
              </div>
              <div id="doc-name-error" class="error"></div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="clearfix"></div>
          <div class="clearfix"></div>
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('upload_doc', __('user.upload_doc') . ':*') !!}
              <div class='input-group date' >
                {!! Form::file('document', ['accept' => 'image/*','class' => 'form-control']); !!}
              </div>
              <div id="document-error" class="error"></div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="form-group">
              {!! Form::label('doc_note', __( 'user.doc_note' ) . ':') !!}
              {!! Form::textarea('doc_note', null, ['class' => 'form-control','placeholder' => __( 'user.doc_note' ), 'rows' => 3 ]); !!}
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
        </div>

        {!! Form::close() !!}

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
</div>
@stop
@push('plugin-scripts')
{!! Html::script('assets/js/loader.js') !!}
{!! Html::script('plugins/table/datatable/datatables.js') !!}
<!-- {!! Html::script('plugins/sweetalerts/sweetalert2.min.js') !!} -->
@endpush
@push('custom-scripts')
<script type="text/javascript">
$(document).ready(function() {
var id = "{{$lastSegment}}";
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
$(document).on('click', 'button.delete_doc_button', function() {
  var id = $(this).data('id');
  willDelete = confirm(LANG.sure);
  if (willDelete) {
    var href = "{{url('user/docDestroy')}}"+"/"+id;
    var data = $(this).serialize();
    $.ajax({
      method: "GET",
      url: href,
      dataType: "json",
      data: data,
      success: function(result) {
        if (result.success == true) {
          toastr.success(result.msg);
        window.LaravelDataTables["#table_new"].ajax.reload();
        } else {
          toastr.error(result.msg);
        }
      }
    });
  }
});
});
</script>
<script>
$(document).ready(function () {
  $("#add_doc").validate({
    rules: {
      'doc_name': {
        required: true,
      },
      'document': {
        required: true,
      }
    },
    messages: {
      'doc_name': {
        required: "Please enter document name",
      },
      'document': {
        required: "Please select document",
      }
    },
    errorPlacement: function (error, element) {
      // Place the error message after the input box
      error.appendTo(element.closest('.form-group'));
    },
    submitHandler: function (form) {
      // If form is valid, submit it
      form.submit();
    }
  });
});
</script>
<script>

$(document).on('click', '#user_doc',function() {
  var dataId = "{{$lastSegment}}";

  // Set the data-id value to the hidden input
  $('#user_id').val(dataId);

  // Show the modal
  $('#myModal').modal('show');
});
</script>
<script>
$(document).ready(function () {
  $("#add_doc").validate({
    rules: {
      'doc_name': {
        required: true,
      },
      'document': {
        required: true,
      }
    },
    messages: {
      'doc_name': {
        required: "Please enter document name",
      },
      'document': {
        required: "Please select document",
      }
    },
    errorPlacement: function (error, element) {
      // Place the error message after the input box
      error.appendTo(element.closest('.form-group'));
    },
    submitHandler: function (form) {
      // If form is valid, submit it
      form.submit();
    }
  });
});
</script>
@endpush
