@extends('ilib::layouts.frontend-wide')
@section('content')
    <div class="ebook viewer">
        <div class="main-heading">
            {!! $ebook->present()->fileicon !!} {{$ebook->title}}
        </div>
        <div class="content">
            <iframe src="/viewer/web/viewer.html?file={{route('ilib.ebook.download', ['ebook' => $ebook->id, 'slug' => $ebook->slug])}}" frameborder="0"></iframe>
        </div>
    </div>
@stop