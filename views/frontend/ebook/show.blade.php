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
                    <div class="security">{!! $ebook->present()->securityFormated('primary') !!}</div>
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
                    <dt>{{trans("ebook::common.category_id")}}</dt>
                    <dd>{{$ebook->category->title}}</dd>
                    <dt>{{trans("ebook::common.language_id")}}</dt>
                    <dd>{{$ebook->language}}</dd>
                    <dt>{{trans("ebook::common.pages")}}</dt>
                    <dd>{{$ebook->pages}}</dd>
                </dl>
                <div class="buttons">
                    <a href="{{route('ilib.ebook.full', ['ebook' => $ebook->id, 'slug' => $ebook->slug])}}" class="btn btn-success">{{trans('common.show')}}</a>
                    <div class="meta">
                        <small>{!! $ebook->present()->fileicon !!} {{$ebook->present()->filesize}}</small>
                    </div>
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