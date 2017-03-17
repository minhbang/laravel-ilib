@extends('ilib::layouts.frontend')
@section('content')
    <div class="ebook">
        <div class="main-heading">
            {!! $ebook->present()->fileicon !!} {{$ebook->title}}
        </div>
        <div class="content">
            <div class="cover">
                <div class="ebook-cover ebook-cover-lg">
                    {!! $ebook->present()->featured_image('img-responsive', false, false, '') !!}
                    <div class="security">{!! $ebook->present()->securityFormated('success') !!}</div>
                </div>
            </div>
            <div class="details">
                <dl class="dl-horizontal">
                    <dt>{{trans("ebook::common.writer_id")}}</dt>
                    <dd>{{$ebook->writer}}</dd>
                    <dt>{{trans("ebook::common.publisher_id")}}</dt>
                    <dd>{{$ebook->publisher}}</dd>
                    <dt>{{trans("ebook::common.pyear")}}</dt>
                    <dd>{{$ebook->pyear}}</dd>
                    <dt>{{trans("ebook::common.pplace_id")}}</dt>
                    <dd>{{$ebook->pplace}}</dd>
                    <dt>{{trans("ebook::common.language_id")}}</dt>
                    <dd>{{$ebook->language}}</dd>
                    <dt>{{trans("ebook::common.pages")}}</dt>
                    <dd>{{$ebook->pages}}</dd>
                    <dt>{{trans("ebook::common.categories")}}</dt>
                    <dd>{!! $ebook->present()->categories !!}</dd>
                </dl>
                <div class="files">
                    <h4 class="files-title">{{trans('ilib::common.ebook_files')}}</h4>
                    {!! $ebook->present()->files('ilib.ebook.view', ['ebook' => $ebook->id, 'slug' => $ebook->slug]) !!}
                </div>
            </div>
            <div class="summary">
                <div class="summary-title">{{trans('ebook::common.summary')}}</div>
                {!! $ebook->summary !!}
            </div>
        </div>
    </div>
    @if($related_ebooks->count())
        <div class="main-heading2">{{trans('ebook::common.related')}}</div>
        <div class="ebooks">
            {!! $ebook_widget->items($related_ebooks, 'th') !!}
        </div>
    @endif
@stop