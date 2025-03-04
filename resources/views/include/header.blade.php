<header class="header navbar navbar-expand-sm">
    <ul class="navbar-item theme-brand flex-row  text-center">
        <li class="nav-item theme-logo">
            <!--a href="{{ url('/') }}">
                <img src="{{ url('assets/img/logo.png') }}" class="navbar-logo" alt="logo">
            </a-->
        </li>
        <li class="nav-item theme-text">
            <a href="{{ url('/') }}" class="nav-link"> Shwetnir Pos </a>
        </li>
    </ul>

    <ul class="navbar-item flex-row ml-md-auto">
        <li class="nav-item dropdown user-profile-dropdown">
            @if (Module::has('Essentials'))
                @includeIf('essentials::layouts.partials.header_part')
            @endif
            <button id="header_shortcut_dropdown" type="button"
                class="btn btn-success dropdown-toggle btn-flat pull-left m-8 btn-sm mt-10" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-plus-circle fa-lg"></i>
            </button>
            <div class="btn-group">

                <ul class="dropdown-menu" style="margin-left: -308px;margin-top: 40px;">
                    @if (config('app.env') != 'demo')
                        <li><a href="{{ route('calendar') }}">
                                <i class="fas fa-calendar-alt" aria-hidden="true"></i> @lang('lang_v1.calendar')
                            </a></li>
                    @endif
                    @if (Module::has('Essentials'))
                        <li><a href="#" class="btn-modal"
                                data-href="{{ action([\Modules\Essentials\Http\Controllers\ToDoController::class, 'create']) }}"
                                data-container="#task_modal">
                                <i class="fas fa-clipboard-check" aria-hidden="true"></i> @lang('essentials::lang.add_to_do')
                            </a></li>
                    @endif
                    <!-- Help Button -->
                    @if (auth()->user()->hasRole('Admin#' . auth()->user()->business_id))
                        <li><a id="start_tour" href="#">
                                <i class="fas fa-question-circle" aria-hidden="true"></i> @lang('lang_v1.application_tour')
                            </a></li>
                    @endif
                </ul>
            </div>
            <button id="btnCalculator" title="@lang('lang_v1.calculator')" type="button"
                class="btn btn-success btn-flat pull-left m-8 btn-sm mt-10 popover-default hidden-xs"
                data-toggle="popover" data-trigger="click" data-content='@include('layouts.partials.calculator')' data-html="true"
                data-placement="bottom">
                <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
            </button>

            @if ($request->segment(1) == 'pos')
                @can('view_cash_register')
                    <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}"
                        data-toggle="tooltip" data-placement="bottom"
                        class="btn btn-success btn-flat pull-left m-8 btn-sm mt-10 btn-modal"
                        data-container=".register_details_modal"
                        data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getRegisterDetails']) }}">
                        <strong><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></strong>
                    </button>
                @endcan
                @can('close_cash_register')
                    <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}"
                        data-toggle="tooltip" data-placement="bottom"
                        class="btn btn-danger btn-flat pull-left m-8 btn-sm mt-10 btn-modal"
                        data-container=".close_register_modal"
                        data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getCloseRegister']) }}">
                        <strong><i class="fa fa-window-close fa-lg"></i></strong>
                    </button>
                @endcan
            @endif

            @if (in_array('pos_sale', $enabled_modules))
                @can('sell.create')
                    <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}"
                        title="@lang('sale.pos_sale')" data-toggle="tooltip" data-placement="bottom"
                        class="btn btn-flat pull-left m-8 btn-sm mt-10 btn-success">
                        <strong><i class="fa fa-th-large"></i> &nbsp; @lang('sale.pos_sale')</strong>
                    </a>
                @endcan
            @endif

            @if (Module::has('Repair'))
                @includeIf('repair::layouts.partials.header')
            @endif

            @can('profit_loss_report.view')
                <button type="button" id="view_todays_profit" title="{{ __('home.todays_profit') }}" data-toggle="tooltip"
                    data-placement="bottom" class="btn btn-success btn-flat pull-left m-8 btn-sm mt-10">
                    <strong><i class="fas fa-money-bill-alt fa-lg"></i></strong>
                </button>
            @endcan

            <div class="m-8 pull-left mt-15 hidden-xs">
                <strong>{{ @format_date('now') }}</strong>
            </div>
        </li>
        {{-- <li class="nav-item dropdown language-dropdown">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="las la-language"></i>
            </a>
            <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                <a class="dropdown-item d-flex" href="javascript:void(0);">
                    <img src="{{ url('assets/img/flag/usa-flag.png') }}" class="flag-width" alt="flag">
                    <span class="align-self-center"> {{ __('English') }}</span>
                </a>
                <a class="dropdown-item d-flex" href="javascript:void(0);">
                    <img src="{{ url('assets/img/flag/spain-flag.png') }}" class="flag-width" alt="flag">
                    <span class="align-self-center">&nbsp;{{ __('Spanish') }}</span>
                </a>
                <a class="dropdown-item d-flex" href="javascript:void(0);">
                    <img src="{{ url('assets/img/flag/france-flag.png') }}" class="flag-width" alt="flag">
                    <span class="align-self-center">&nbsp;{{ __('French') }}</span>
                </a>
                <a class="dropdown-item d-flex" href="javascript:void(0);">
                    <img src="{{ url('assets/img/flag/saudi-arabia-flag.png') }}" class="flag-width" alt="flag">
                    <span class="align-self-center">&nbsp;{{ __('Arabic') }}</span>
                </a>
            </div>
        </li> --}}

        {{-- <li class="nav-item dropdown notification-dropdown">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle position-relative" id="notificationDropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="las la-bell"></i>
                <div class="blink">
                    <div class="circle"></div>
                </div>
            </a>
            <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                <div class="nav-drop is-notification-dropdown">
                    <div class="inner">
                        <div class="nav-drop-header">
                            <span class="text-black font-12 strong">{{ __('5 Notifications') }}</span>
                            <a class="text-muted font-12" href="#">
                                {{ __('Clear All') }}
                            </a>
                        </div>
                        <div class="nav-drop-body account-items pb-0">
                            <a class="account-item" href="{{ url('/apps/ecommerce/orders') }}">
                                <div class="media align-center">
                                    <div class="icon-wrap">
                                        <i class="las la-box font-20"></i>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong">{{ __('2 New orders placed') }}</h6>
                                        <p class="m-0 mt-1 font-10 text-muted"> {{ __('10 sec ago') }}</p>
                                    </div>
                                </div>
                            </a>
                            <a class="account-item" href="javascript:void(0)">
                                <div class="media align-center">
                                    <div class="icon-wrap">
                                        <i class="las la-user-plus font-20"></i>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong"> {{ __('New User registered') }}</h6>
                                        <p class="m-0 mt-1 font-10 text-muted"> {{ __('5 minute ago') }}</p>
                                    </div>
                                </div>
                            </a>
                            <a class="account-item" href="{{ url('/apps/tickets/list') }}">
                                <div class="media align-center">
                                    <div class="icon-wrap">
                                        <i class="las la-grin-beam font-20"></i>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong">{{ __('21 Queries solved') }}</h6>
                                        <p class="m-0 mt-1 font-10 text-muted"> {{ __('1 hour ago') }}</p>
                                    </div>
                                </div>
                            </a>
                            <a class="account-item" href="javascript:void(0)">
                                <div class="media align-center">
                                    <div class="icon-wrap">
                                        <i class="las la-cloud-download-alt font-20"></i>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong"> {{ __('New update available') }}</h6>
                                        <p class="m-0 mt-1 font-10 text-muted"> {{ __('1 day ago') }}</p>
                                    </div>
                                </div>
                            </a>
                            <hr class="account-divider">
                            <div class="text-center">
                                <a class="text-primary strong font-13" href="{{ url('/pages/notifications') }}">
                                    {{ __('View All') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li> --}}

        @include('layouts.partials.header-notifications')
        <li class="nav-item dropdown user-profile-dropdown">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                @if (!empty(Session::get('business.logo')))
                    <img src="{{ asset('uploads/business_logos/' . Session::get('business.logo')) }}"
                        alt="{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}">
                @else
                    <img src="{{ url('assets/img/logo.png') }}"
                        alt="{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}">
                @endif
            </a>
            <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                <div class="nav-drop is-account-dropdown">
                    <div class="inner">
                        <div class="nav-drop-header">
                            <span
                                class="text-primary font-15">{{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }}
                            </span>
                        </div>
                        <div class="nav-drop-body account-items pb-0">
                            {{-- <a id="profile-link" class="account-item"
                                href="{{ action([\App\Http\Controllers\UserController::class, 'getProfile']) }}">
                                <div class="media align-center">
                                    <div class="media-left">
                                        <div class="image">
                                            @if (!empty(Session::get('business.logo')))
                                                <img class="rounded-circle avatar-xs"
                                                    src="{{ asset('uploads/business_logos/' . Session::get('business.logo')) }}"
                                                    alt="{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}">
                                            @else
                                                <img class="rounded-circle avatar-xs"
                                                    src="{{ url('assets/img/logo.png') }}"
                                                    alt="{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong">{{ Auth::User()->first_name }}
                                            {{ Auth::User()->last_name }}</h6>
                                        <small>{{ Session::get('business.email') }}</small>
                                    </div>
                                    <div class="media-right">
                                        <i data-feather="check"></i>
                                    </div>
                                </div>
                            </a> --}}

                            <a class="account-item"
                                href="{{ action([\App\Http\Controllers\UserController::class, 'getProfile']) }}">
                                <div class="media align-center">
                                    <div class="icon-wrap">
                                        <i class="las la-user font-20"></i>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong"> @lang('lang_v1.profile')</h6>
                                    </div>
                                </div>
                            </a>

                            <hr class="account-divider">

                            <a class="account-item"
                                href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'logout']) }}">
                                <div class="media align-center">
                                    <div class="icon-wrap">
                                        <i class="las la-sign-out-alt font-20"></i>
                                    </div>
                                    <div class="media-content ml-3">
                                        <h6 class="font-13 mb-0 strong ">@lang('lang_v1.sign_out')</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>

</header>
