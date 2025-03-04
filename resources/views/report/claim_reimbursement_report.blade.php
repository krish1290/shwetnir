@extends('layouts.app')
@section('title', __('report.claim_reimbursement_report'))

@section('content')

    <!-- Content Header (Page header) -->
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
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('report.claim_reimbursement_report')</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">@lang('report.claim_reimbursement_report_msg')</a></li>

                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('status', __('report.status') . ':') !!}
                            {!! Form::select('status', $status, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                                'id'=>'get-status'
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('claim_reimbursement_report_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('claim_reimbursement_report_date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'claim_reimbursement_report_date_range',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">

                    <div class="tab-content date-table-container">
                        <div class="tab-pane active" id="input_tax_tab">
                          <table class="table table-bordered table-striped" id="ad_pc_table" style="width: 100%;">
                              <thead>
                                  <tr>
                                      <th>@lang('lang_v1.description')</th>
                                      <th>@lang('lang_v1.type')</th>
                                      <th>@lang('sale.amount')</th>
                                      <th>@lang('essentials::lang.date')</th>
                                      <th>@lang('essentials::lang.employee')</th>
                                      <th>status</th>
                                  </tr>
                              </thead>
                          </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="add_allowance_deduction_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel"></div>
    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
          $('#claim_reimbursement_report_date_range').daterangepicker(
              dateRangeSettings,
              function(start, end) {
                  $('#claim_reimbursement_report_date_range').val(
                      start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                  );
              }
          );
          ad_pc_table = $('#ad_pc_table').DataTable({
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
              language: {
                  "paginate": {
                      "previous": "<i class='las la-angle-left'></i>",
                      "next": "<i class='las la-angle-right'></i>"
                  }
              },
              processing: true,
              serverSide: true,
              ajax: {
                  url: '/reports/claim-reimbursement-details',
                  data: function(d) {
                      d.status = $('#get-status').val();
                      var start = $('input#claim_reimbursement_report_date_range')
                          .data('daterangepicker')
                          .startDate.format('YYYY-MM-DD');
                      var end = $('input#claim_reimbursement_report_date_range')
                          .data('daterangepicker')
                          .endDate.format('YYYY-MM-DD');
                      d.start_date = start;
                      d.end_date = end;
                  }
              },
              columns: [{
                      data: 'description',
                      name: 'description'
                  },
                  {
                      data: 'type',
                      name: 'type'
                  },
                  {
                      data: 'amount',
                      name: 'amount'
                  },
                  {
                      data: 'applicable_date',
                      name: 'applicable_date'
                  },
                  {
                      data: 'employees',
                      searchable: false,
                      orderable: true
                  },
                  {
                      data: 'is_approved',
                      searchable: true,
                      orderable: true

                  }
              ],
              fnDrawCallback: function(oSettings) {
                  __currency_convert_recursively($('#ad_pc_table'));
              },
          });
          $('#claim_reimbursement_report_date_range, #get-status').change(function() {
            $('#ad_pc_table').DataTable().ajax.reload();
              });
          $(document).on('click', 'a.change_status', function(e) {
              e.preventDefault();
              $('#change_status_modal').find('select#status_dropdown').val($(this).data('orig-value'))
                  .change();
              $('#change_status_modal').find('#leave_id').val($(this).data('leave-id'));
              $('#change_status_modal').find('#status_note').val($(this).data('status_note'));
              $('#change_status_modal').modal('show');
          });
          $(document).on('submit', 'form#change_status_form', function(e) {
              e.preventDefault();
              var data = $(this).serialize();
              var ladda = Ladda.create(document.querySelector('.update-leave-status'));
              ladda.start();
              $.ajax({
                  method: $(this).attr('method'),
                  url: $(this).attr('action'),
                  dataType: 'json',
                  data: data,
                  success: function(result) {
                      ladda.stop();
                      if (result.success == true) {
                          $('div#change_status_modal').modal('hide');
                          toastr.success(result.msg);
                          leaves_table.ajax.reload();
                      } else {
                          toastr.error(result.msg);
                      }
                  },
              });
          });
        });
    </script>
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection
