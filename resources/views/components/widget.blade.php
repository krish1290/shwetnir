<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow  {{ $class ?? 'box-solid' }}"
        @if (!empty($id)) id="{{ $id }}" @endif>
        @if (empty($header))
            @if (!empty($title) || !empty($tool))
                <div class="widget-header">
                    {!! $icon ?? '' !!}
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{ $title ?? '' }}</h4>
                        </div>
                    </div>

                    {!! $tool ?? '' !!}
                </div>
            @endif
        @else
            <div class="widget-header">
                {!! $header !!}
            </div>
        @endif

        <div class="widget-content widget-content-area">
            {{ $slot }}
        </div>
        <!-- /.box-body -->
    </div>
</div>
