@extends('layouts.app')
@section('title', __('user.edit_user'))
@push('plugin-styles')
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
              <li class="breadcrumb-item"><a href="{{ url('users') }}">@lang('user.users')</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>@lang('user.edit_user')</span></li>
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
              @can('user.create')
              {!! Form::open([
                'url' => action([\App\Http\Controllers\ManageUserController::class, 'update'], [$user->id]),
                'method' => 'PUT',
                'id' => 'user_edit_form',
                ]) !!}
                <div class="row">
                  <div class="col-md-12">
                    @component('components.widget', ['class' => 'box-primary'])
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          {!! Form::label('surname', __('business.prefix') . ':') !!}
                          {!! Form::text('surname', $user->surname, [
                          'class' => 'form-control',
                          'placeholder' => __('business.prefix_placeholder'),
                          ]) !!}
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          {!! Form::label('first_name', __('business.first_name') . ':*') !!}
                          {!! Form::text('first_name', $user->first_name, [
                          'class' => 'form-control',
                          'required',
                          'placeholder' => __('business.first_name'),
                          ]) !!}
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          {!! Form::label('last_name', __('business.last_name') . ':') !!}
                          {!! Form::text('last_name', $user->last_name, [
                          'class' => 'form-control',
                          'placeholder' => __('business.last_name'),
                          ]) !!}
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-4">
                        <div class="form-group">
                          {!! Form::label('email', __('business.email') . ':*') !!}
                          {!! Form::text('email', $user->email, [
                          'class' => 'form-control',
                          'required',
                          'placeholder' => __('business.email'),
                          ]) !!}
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          {!! Form::label('parent_id', __('Manager') . ':') !!}
                          {!! Form::select('parent_id', $mangers, $user->parent_id, ['class' => 'form-control select2']) !!}
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="checkbox">
                            <br>
                            <br>
                            <label>
                              {!! Form::checkbox('is_active', $user->status, $is_checked_checkbox, ['class' => 'input-icheck status']) !!} {{ __('lang_v1.status_for_user') }}
                            </label>
                            @show_tooltip(__('lang_v1.tooltip_enable_user_active'))
                          </div>
                        </div>
                      </div>
                    </div>
                    @endcomponent
                  </div>
                  <div class="col-md-12">
                    <div class="row">
                      @component('components.widget', ['title' => __('lang_v1.roles_and_permissions')])
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <div class="checkbox">
                              <label>
                                {!! Form::checkbox('allow_login', 1, !empty($user->allow_login), [
                                'class' => 'input-icheck',
                                'id' => 'allow_login',
                                ]) !!} {{ __('lang_v1.allow_login') }}
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="clearfix"></div>

                      <div class="user_auth_fields @if (empty($user->allow_login)) hide @endif">
                        <div class="row">
                          @if (empty($user->allow_login))
                          <div class="col-md-4">
                            <div class="form-group">
                              {!! Form::label('username', __('business.username') . ':') !!}
                              @if (!empty($username_ext))
                              <div class="input-group">
                                {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => __('business.username')]) !!}
                                <span
                                class="input-group-addon">{{ $username_ext }}</span>
                              </div>
                              <p class="help-block" id="show_username"></p>
                              @else
                              {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => __('business.username')]) !!}
                              @endif
                              <p class="help-block">@lang('lang_v1.username_help')</p>
                            </div>
                          </div>
                          @endif
                          <div class="col-md-4">
                            <div class="form-group">
                              {!! Form::label('password', __('business.password') . ':') !!}
                              {!! Form::password('password', [
                              'class' => 'form-control',
                              'placeholder' => __('business.password'),
                              'required' => empty($user->allow_login) ? true : false,
                              ]) !!}
                              <p class="help-block">@lang('user.leave_password_blank')</p>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              {!! Form::label('confirm_password', __('business.confirm_password') . ':') !!}
                              {!! Form::password('confirm_password', [
                              'class' => 'form-control',
                              'placeholder' => __('business.confirm_password'),
                              'required' => empty($user->allow_login) ? true : false,
                              ]) !!}

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="clearfix"></div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            {!! Form::label('role', __('user.role') . ':*') !!}
                            @show_tooltip(__('lang_v1.admin_role_location_permission_help'))
                            {!! Form::select('role', $roles, !empty($user->roles->first()->id) ? $user->roles->first()->id : null, [
                            'class' => 'form-control select2',
                            'style' => 'width: 100%;',
                            ]) !!}
                          </div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="row">
                        <div class="col-md-3">
                          <h4 style="font-style:18px;">@lang('role.access_locations')
                            @show_tooltip(__('tooltip.access_locations_permission'))
                          </h4>
                        </div>
                        <div class="col-md-9">
                          <div class="col-md-12">
                            <div class="checkbox">
                              <label>
                                {!! Form::checkbox(
                                  'access_all_locations',
                                  'access_all_locations',
                                  !is_array($permitted_locations) && $permitted_locations == 'all',
                                  ['class' => '', 'id' => 'access_all_location']
                                  ) !!} {{ __('role.all_locations') }}
                                </label>
                                @show_tooltip(__('tooltip.all_location_permission'))
                              </div>
                            </div>
                           
                            @foreach ($locations as $location)
                          
                            <div class="col-md-12">
                              <div class="checkbox">
                                <label>
                                {!! Form::checkbox(
                                  'location_permissions[]',
                                  'location.' . $location->id,
                                  is_array(old('location_permissions', $permitted_locations)) && 
                                  (in_array('location.' . $location->id, old('location_permissions', $permitted_locations)) || 
                                  in_array('all', old('location_permissions', $permitted_locations))),
                                  ['class' => 'location-checkbox']
                              ) !!} {{ $location->name }}
                              @if (!empty($location->location_id))
                                  ({{ $location->location_id }})
                              @endif


                                  </label>
                                </div>
                              </div>
                              @endforeach
                            </div>
                          </div>
                          @endcomponent
                        </div>
                      </div>

                      <div class="col-md-12">
                        @component('components.widget', ['title' => __('sale.sells')])
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              {!! Form::label('cmmsn_percent', __('lang_v1.cmmsn_percent') . ':') !!}
                              @show_tooltip(__('lang_v1.commsn_percent_help'))
                              {!! Form::text('cmmsn_percent', !empty($user->cmmsn_percent) ? @num_format($user->cmmsn_percent) : 0, [
                              'class' => 'form-control input_number',
                              'placeholder' => __('lang_v1.cmmsn_percent'),
                              ]) !!}
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group">
                              {!! Form::label('max_sales_discount_percent', __('lang_v1.max_sales_discount_percent') . ':') !!}
                              @show_tooltip(__('lang_v1.max_sales_discount_percent_help'))
                              {!! Form::text(
                                'max_sales_discount_percent',
                                !is_null($user->max_sales_discount_percent) ? @num_format($user->max_sales_discount_percent) : null,
                                ['class' => 'form-control input_number', 'placeholder' => __('lang_v1.max_sales_discount_percent')],
                                ) !!}
                              </div>
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <div class="checkbox">
                                  <br />
                                  <label>
                                    {!! Form::checkbox('selected_contacts', 1, $user->selected_contacts, [
                                    'class' => 'input-icheck',
                                    'id' => 'selected_contacts',
                                    ]) !!}
                                    {{ __('lang_v1.allow_selected_contacts') }}
                                  </label>
                                  @show_tooltip(__('lang_v1.allow_selected_contacts_tooltip'))
                                </div>
                              </div>
                            </div>
                            <div
                            class="col-sm-4 selected_contacts_div @if (!$user->selected_contacts) hide @endif">
                            <div class="form-group">
                              {!! Form::label('user_allowed_contacts', __('lang_v1.selected_contacts') . ':') !!}
                              <div class="form-group">
                                {!! Form::select('selected_contact_ids[]', $contact_access, array_keys($contact_access), [
                                'class' => 'form-control select2',
                                'multiple',
                                'style' => 'width: 100%;',
                                'id' => 'user_allowed_contacts',
                                ]) !!}
                              </div>
                            </div>
                          </div>
                        </div>
                        @endcomponent
                      </div>
                    </div>
                    @include('user.edit_profile_form_part', [
                    'bank_details' => !empty($user->bank_details)
                    ? json_decode($user->bank_details, true)
                    : null,
                    ])

                    @if (!empty($form_partials))
                    @foreach ($form_partials as $partial)
                    {!! $partial !!}
                    @endforeach
                    @endif
                    <div class="row">
                      <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-big"
                        id="submit_user_button">@lang('messages.update')</button>
                      </div>
                    </div>
                    {!! Form::close() !!}
                    @endcan

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
      {!! Html::script('plugins/jquery-validation/dist/jquery.validate.min.js') !!}
      @endpush
      @push('custom-scripts')
      <script>
    $(document).ready(function() {
        // Uncheck the checkbox on page load
        $('#access_all_location').prop('checked', false);
    });
</script>
      <script type="text/javascript">
      __select2($('.select2'));
      __page_leave_confirmation('#user_edit_form');
      $(document).ready(function() {
        $('#selected_contacts').on('ifChecked', function(event) {
          $('div.selected_contacts_div').removeClass('hide');
        });
        $('#selected_contacts').on('ifUnchecked', function(event) {
          $('div.selected_contacts_div').addClass('hide');
        });

        $('#allow_login').on('ifChecked', function(event) {
          $('div.user_auth_fields').removeClass('hide');
        });
        $('#allow_login').on('ifUnchecked', function(event) {
          $('div.user_auth_fields').addClass('hide');
        });

        $('#user_allowed_contacts').select2({
          ajax: {
            url: "{{ env('APP_URL') }}/contacts/customers",
            dataType: 'json',
            delay: 250,
            data: function(params) {
              return {
                q: params.term, // search term
                page: params.page,
                all_contact: true
              };
            },
            processResults: function(data) {
              return {
                results: data,
              };
            },
          },
          templateResult: function(data) {
            var template = '';
            if (data.supplier_business_name) {
              template += data.supplier_business_name + "<br>";
            }
            template += data.text + "<br>" + LANG.mobile + ": " + data.mobile;

            return template;
          },
          minimumInputLength: 1,
          escapeMarkup: function(markup) {
            return markup;
          },
        });
      });

      $('form#user_edit_form').validate({
        rules: {
          first_name: {
            required: true,
          },
          email: {
            email: true,
            remote: {
              url: "/business/register/check-email",
              type: "post",
              data: {
                email: function() {
                  return $("#email").val();
                },
                user_id: "{{ $user->id }}"
              }
            }
          },
          password: {
            minlength: 5
          },
          confirm_password: {
            equalTo: "#password"
          },
          username: {
            minlength: 5,
            remote: {
              url: "{{ env('APP_URL') }}/business/register/check-username",
              type: "post",
              data: {
                username: function() {
                  return $("#username").val();
                },
                user_id: "{{ $user->id }}",
                @if (!empty($username_ext))
                username_ext: "{{ $username_ext }}"
                @endif
              }
            }
          }
        },
        messages: {
          password: {
            minlength: 'Password should be minimum 5 characters',
          },
          confirm_password: {
            equalTo: 'Should be same as password'
          },
          username: {
            remote: 'Invalid username or User already exist'
          },
          email: {
            remote: '{{ __('validation.unique', ['attribute' => __('business.email')]) }}'
          }
        }
      });
      $('#username').change(function() {
        if ($('#show_username').length > 0) {
          if ($(this).val().trim() != '') {
            $('#show_username').html("{{ __('lang_v1.your_username_will_be') }}: <b>" + $(this).val() +
            "{{ $username_ext }}</b>");
          } else {
            $('#show_username').html('');
          }
        }
      });
      </script>
      <script>
      $(document).ready(function () {
        // Event handler for individual location checkboxes
        $('.location-checkbox').on('change', function () {
          // Check if all location checkboxes are checked
          var allChecked = $('.location-checkbox:checked').length === $('.location-checkbox').length;

          // Update the "All Locations" checkbox accordingly
          $('#access_all_location').prop('checked', allChecked);
        });

        // Event handler for the "All Locations" checkbox
        $('#access_all_location').on('change', function () {
          // If "All Locations" checkbox is checked, check all individual location checkboxes
          if ($(this).prop('checked')) {
            $('.location-checkbox').prop('checked', true);
          } else {
            // If "All Locations" checkbox is unchecked, uncheck all individual location checkboxes
            $('.location-checkbox').prop('checked', false);
          }
        });
      });

    document.addEventListener('DOMContentLoaded', function() {
    let permittedLocations = @json($permitted_locations);

    document.querySelectorAll('.location-checkbox').forEach(function(checkbox) {
        let locationId = checkbox.value.split('.')[1]; // Get the location ID from the value
        if (permittedLocations.includes('location.' + locationId) || permittedLocations.includes('all')) {
            checkbox.checked = true;
        }
    });
});

    </script>
    @endpush
