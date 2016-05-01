@extends('ilib::layouts.frontend')
@section('content')
    <div class="ilib-category">
        <div class="main-heading">
            <i class="fa fa-folder-open-o text-success"></i> {{$category->title}}
        </div>

        @include('_display_options', ['options' => $category_options, 'page_hint' => trans('ilib::common.page_hint')])

        <div class="ebooks">
            {!! $ebook_widget->items($ebooks, $category_options->get('type')) !!}
            <div class="text-center">
                {!! $ebooks->links() !!}
            </div>
        </div>
    </div>
@stop