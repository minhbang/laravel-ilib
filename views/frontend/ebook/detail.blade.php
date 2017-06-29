@extends('ilib::layouts.frontend')
<?php /** @var Minhbang\Ebook\Ebook $ebook */?>
@section('content')
    <div class="ebook">
        <div class="main-heading">
            {{$ebook->title}}
        </div>
        <div class="content">
            <div class="cover">
                <div class="ebook-cover ebook-cover-lg">
                    {!! $ebook->present()->featured_image('img-responsive', false, false, '') !!}
                    <div class="security">{!! $ebook->present()->security('success') !!}</div>
                </div>
            </div>
            <div class="details">
                <dl class="dl-horizontal">
                    <dt>{{trans("ebook::common.writer_id")}}</dt>
                    <dd>{{$ebook->writer_title}}</dd>
                    <dt>{{trans("ebook::common.publisher_id")}}</dt>
                    <dd>{{$ebook->publisher_title}}</dd>
                    <dt>{{trans("ebook::common.pyear")}}</dt>
                    <dd>{{$ebook->pyear}}</dd>
                    <dt>{{trans("ebook::common.pplace_id")}}</dt>
                    <dd>{{$ebook->pplace_title}}</dd>
                    <dt>{{trans("ebook::common.category_id")}}</dt>
                    <dd>{{$ebook->category->title}}</dd>
                    <dt>{{trans("ebook::common.language_id")}}</dt>
                    <dd>{{$ebook->language_title}}</dd>
                    <dt>{{trans("ebook::common.pages")}}</dt>
                    <dd>{{$ebook->pages}}</dd>
                    <dt>{{trans("common.updated_at")}}</dt>
                    <dd>{{$ebook->present()->updatedAt}}</dd>
                </dl>
                <div class="files">
                    <h5><i class="fa fa-book"></i> {{trans('ilib::common.view')}}</h5>
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