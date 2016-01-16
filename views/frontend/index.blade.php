@extends('ilib::layouts.frontend')
@section('content')
    <div class="ilib-home">
        <div class="ebooks">
            {!! $ebook_widget->items($ebook_featured, $home_options->get('type')) !!}
        </div>
        @include('ilib::frontend._display_options', ['options' => $home_options])
        <div class="main-heading2">{{trans('ilib::common.latest')}}</div>
        <div class="ebooks">
            {!! $ebook_widget->items($ebook_latest, $home_options->get('type')) !!}
        </div>
    </div>
@stop