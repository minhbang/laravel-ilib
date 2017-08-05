@extends('ilib::layouts.frontend-wide')
@section('content')
    <div class="ebook viewer">
        <div class="content">
            <iframe src="/mbPDFjs/web/viewer.html?locale={{$locale}}&file={{$url}}" frameborder="0"></iframe>
        </div>
    </div>
@stop

