@extends('ilib::layouts.frontend')
@section('content')
    <div class="page-category">
        <div class="main-heading">
            <i class="fa fa-folder-open-o text-success"></i> {{$category->title}}
        </div>
        <div class="main-meta">
            <div class="form form-inline">
                <div class="buttons">
                    {!! $category_options->link('type', 'th', 'th', trans('ilib::common.display_th')) !!}
                    {!! $category_options->link('type', 'list', 'list', trans('ilib::common.display_list')) !!}
                </div>
                <div class="pull-right">
                    <div class="form-group">
                        {!! $category_options->select('sort', trans('ilib::common.sort'), trans('ilib::common.sort_hint')) !!}
                    </div>
                    <div class="form-group">
                        {!! $category_options->select('page_size', trans('ilib::common.page_size'), trans('ilib::common.page_size_hint')) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="ebooks">
            {!! $ebook_widget->items($ebooks, $category_options->get('type')) !!}
            <div class="text-center">
                {!! $ebooks->links() !!}
            </div>
        </div>
    </div>
@stop