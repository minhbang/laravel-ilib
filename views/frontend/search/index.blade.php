@extends('ilib::layouts.frontend')
@section('content')
    <div class="ilib-search">
        <div class="main-heading">
            <i class="fa fa-search text-success"></i> {{trans('ilib::common.search_result')}}:
            @if($total)
                <small class="text-danger">{{$total}} {{trans('ebook::common.ebook')}}</small>
            @endif
        </div>
        <div class="form search">
            {!! Form::model($params, ['route' => 'ilib.search', 'method' => 'get']) !!}
            <div class="input-group">
                {!! Form::text('q', $q, ['id' => 'form_search', 'class' => 'query input-lg form-control', 'placeholder' => trans('common.keyword').'...']) !!}
                <span class="input-group-btn">
                    <button class="btn btn-success btn-lg" type="submit">
                        <i class="fa fa-search"></i> {{trans('common.search')}}
                    </button>
                    <a id="btn-search-advanced" href="#search-advanced" class="btn btn-default btn-lg" role="button"
                        data-toggle="collapse" aria-expanded="{{ $advanced ? 'true':'false' }}"
                        aria-controls="search-advanced">
                        {{trans('common.advanced')}} <i class="fa"></i>
                    </a>
                </span>
            </div>
            <div class="collapse {{ $advanced ? ' in':'' }}" id="search-advanced" aria-expanded="{{ $advanced ?
            'true':'false' }}">
                <div class="form-group">
                    {!! Form::label('category_id', trans('category::common.category'), ['class' => 'control-label']) !!}
                    {!! Form::select($column_key['category_id'], $categories, null, ['prompt' =>trans('common.all'), 'class' => 'form-control selectize-tree']) !!}
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            {!! Form::label('security_id', trans('ebook::common.security_id'), ['class' => 'control-label']) !!}
                            {!! Form::select($column_key['security_id'], $securities, null, ['prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('writer_id', trans('ebook::common.writer_id'), ['class' => 'control-label']) !!}
                            {!! Form::select($column_key['writer_id'], $writers, null, ['prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('pyear', trans('ebook::common.pyear'), ['class' => 'control-label']) !!}
                            <div class="input-group">
                                {!! Form::text($column_key['pyear_start'], null, ['class' => 'form-control', 'placeholder' => trans('common.from').'...']) !!}
                                <span class="input-group-addon"><span class="glyphicon glyphicon-arrow-right"></span></span>
                                {!! Form::text($column_key['pyear_end'], null, ['class' => 'form-control', 'placeholder' => trans('common.to').'...']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            {!! Form::label('language_id', trans('ebook::common.language_id'), ['class' => 'control-label']) !!}
                            {!! Form::select($column_key['language_id'], $languages, null, ['prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('publisher_id', trans('ebook::common.publisher_id'), ['class' => 'control-label']) !!}
                            {!! Form::select($column_key['publisher_id'], $publishers, null, ['prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                        </div>
                        <div class="form-group}">
                            {!! Form::label('pplace_id', trans('ebook::common.pplace_id'), ['class' =>'control-label']) !!}
                            {!! Form::select($column_key['pplace_id'], $pplaces, null, ['prompt'=>trans('common.all'), 'class' => 'form-control selectize']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        @if($total)
            @include('ilib::frontend._display_options', ['options' => $search_options])
        @endif
        <div class="ebooks">
            @if($total)
                {!! $ebook_widget->items($ebooks, $search_options->get('type')) !!}
                <div class="text-center">
                    {!! $ebooks->appends($params)->links() !!}
                </div>
            @else
                <div class="alert alert-danger text-center">{{trans('ilib::common.search_empty')}}</div>
            @endif
        </div>
    </div>
@stop

@section('script-module')
    <script type="text/javascript">
        var collapse_icon = $('#btn-search-advanced').find('.fa'),
            collapse = $('#search-advanced');
        function updateCollapseIcon() {
            if (collapse.hasClass('in')) {
                collapse_icon.removeClass("fa-chevron-down").addClass("fa-chevron-up");
            } else {
                collapse_icon.removeClass("fa-chevron-up").addClass("fa-chevron-down");
            }
        }
        collapse.on('shown.bs.collapse', function () {
            updateCollapseIcon();
        }).on('hidden.bs.collapse', function () {
            updateCollapseIcon();
        });
        updateCollapseIcon();
    </script>
@stop