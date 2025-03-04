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
              <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('user.users')</a></li>
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
                  <h4>@lang('user.manage_users')</h4>
                  @can('user.create')
                  <div class="box-tools">
                    <a class="btn btn-block btn-primary"
                    href="{{ action([\App\Http\Controllers\ManageUserController::class, 'create']) }}">
                    <i class="las la-plus"></i> @lang('messages.add')</a>
                  </div>
                  @endcan
                </div>
                @can('user.view')
                <div class="table-responsive mb-4">
                  <table id="users_table" class="table table-hover" style="width:100%">
                    <thead>
                      <tr>
                        <th>@lang('business.username')</th>
                        <th>@lang('user.name')</th>
                        <th>@lang('user.role')</th>
                        <th>@lang('business.email')</th>
                        <th>@lang('messages.action')</th>
                      </tr>
                    </thead>

                    <tfoot>
                      <tr>
                        <th>@lang('business.username')</th>
                        <th>@lang('user.name')</th>
                        <th>@lang('user.role')</th>
                        <th>@lang('business.email')</th>
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
@stop
@push('plugin-scripts')
{!! Html::script('assets/js/loader.js') !!}
{!! Html::script('plugins/table/datatable/datatables.js') !!}
<!--  The following JS library files are loaded to use Copy CSV Excel Print Options-->
{!! Html::script('plugins/table/datatable/button-ext/dataTables.buttons.min.js') !!}
{!! Html::script('plugins/table/datatable/button-ext/jszip.min.js') !!}
{!! Html::script('plugins/table/datatable/button-ext/buttons.html5.min.js') !!}
{!! Html::script('plugins/table/datatable/button-ext/buttons.print.min.js') !!}
<!-- The following JS library files are loaded to use PDF Options-->
{!! Html::script('plugins/table/datatable/button-ext/pdfmake.min.js') !!}
{!! Html::script('plugins/table/datatable/button-ext/vfs_fonts.js') !!}
<!-- {!! Html::script('plugins/sweetalerts/sweetalert2.min.js') !!} -->
@endpush
@push('custom-scripts')
<script type="text/javascript">
$(document).ready(function() {

  var users_table = $('#users_table').DataTable({
    dom: '<"row"<"col-md-6"B><"col-md-6"f> ><""rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>>',
    buttons: {
      buttons: [{
        extend: 'copy',
        className: 'btn btn-primary'
      },
      {
        extend: 'csv',
        className: 'btn btn-primary'
      },
      {
        extend: 'excel',
        className: 'btn btn-primary'
      },
      {
        extend: 'pdf',
        className: 'btn btn-primary'
      },
      {
        extend: 'print',
        className: 'btn btn-primary'
      }
    ]
  },
  processing: true,
  serverSide: true,
  language: {
    "paginate": {
      "previous": "<i class='las la-angle-left'></i>",
      "next": "<i class='las la-angle-right'></i>"
    }
  },
  lengthMenu: [5, 10, 15, 20],
  pageLength: 5,
  ajax: '/users',
  columnDefs: [{
    "targets": [4],
    "orderable": false,
    "searchable": false
  }],
  "columns": [{
    "data": "username"
  },
  {
    "data": "full_name"
  },
  {
    "data": "role"
  },
  {
    "data": "email"
  },
  {
    "data": "action"
  }
]
});
$(document).on('click', 'button.delete_user_button', function() {
  willDelete = confirm(LANG.sure + "\n" + LANG.confirm_delete_user);
  if (willDelete) {
    var href = $(this).data('href');
    var data = $(this).serialize();
    $.ajax({
      method: "DELETE",
      url: href,
      dataType: "json",
      data: data,
      success: function(result) {
        if (result.success == true) {
          toastr.success(result.msg);
          users_table.ajax.reload();
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

$(document).on('click', '#user_doc',function() {
  var dataId = $(this).data('id');

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
