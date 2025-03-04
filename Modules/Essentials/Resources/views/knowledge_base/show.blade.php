@extends('layouts.app')

@section('title', __('essentials::lang.knowledge_base'))

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
                                <li class="breadcrumb-item"><a href="{{ url('knowledge-base') }}">@lang('essentials::lang.knowledge_base')</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><span>{{ $kb_object->title }}</span>
                                </li>

                            </ol>
                        </nav>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                @include('essentials::knowledge_base.sidebar', [
                    'knowledge_base' => $knowledge_base,
                    'current_id' => $kb_object->id,
                    'article_id' => $article_id,
                    'section_id' => $section_id,
                ])
            </div>
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h4 class="box-title">{{ $kb_object->title }}</h4>
                        @if (!empty($kb_object->share_with))
                            <br>
                            <small><b>@lang('essentials::lang.share_with'):</b> @lang('essentials::lang.' . $kb_object->share_with) @if ($kb_object->share_with == 'only_with')
                                    ({{ implode(', ', $users) }})
                                @endif
                            </small>
                        @endif
                    </div>
                    <div class="box-body">
                        {!! $kb_object->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
