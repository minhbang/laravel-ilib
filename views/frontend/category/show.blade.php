@extends('ilib::layouts.frontend')
@section('content')
    <div class="ilib-category">
        @include('kit::_display_options', ['options' => $category_options, 'page_hint' => trans('ilib::common.page_hint')])
        <?php
        $display_type = $category_options->get('type', 'th');
        $cols = $display_type == 'list' ? 'col-md-12' : 'col-md-4 col-sm-4 col-xs-6';
        ?>
        <div class="ebooks">
            @if($ebooks)
                <div class="row">
                    @foreach($ebooks as $ebook)
                        <div class="{{$cols}}">
                            @include("ebook::frontend._ebook_summary_{$display_type}", compact('ebook'))
                        </div>
                    @endforeach
                </div>
                <div class="text-center">
                    {!! $ebooks->appends(['type' => $display_type])->links() !!}
                </div>
            @else
                <div class="alert alert-danger">{{trans('ilib::common.empty_items')}}</div>
            @endif
        </div>
    </div>
@stop