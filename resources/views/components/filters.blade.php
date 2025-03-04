<div class="col-lg-12 layout-spacing">
    <div class="card @if (!empty($class)) {{ $class }} @endif" id="accordion">
        <div class="card-header" style="cursor: pointer; ">
            <h2 class="mb-0  d-flex" style="font-size:18px;">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFilter">
                    @if (!empty($icon))
                        {!! $icon !!}
                    @else
                        <i class="la la-filter" aria-hidden="true"></i>
                    @endif {{ $title ?? '' }}
                </a>
            </h2>
        </div>
        @php
            if (isMobile()) {
                $closed = true;
            }
        @endphp
        <div id="collapseFilter" class="panel-collapse active collapse @if (empty($closed)) show @endif"
            aria-expanded="true">
            <div class="card-body">
                <div class="row">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
