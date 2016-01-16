<div class="display-options">
    <div class="form form-inline">
        <div class="buttons">
            {!! $options->link('type', 'th', 'th', trans('ilib::common.display_th')) !!}
            {!! $options->link('type', 'list', 'list', trans('ilib::common.display_list')) !!}
        </div>
        <div class="pull-right">
            <div class="form-group">
                {!! $options->select('sort', trans('ilib::common.sort'), trans('ilib::common.sort_hint')) !!}
            </div>
            <div class="form-group">
                {!! $options->select('page_size', trans('ilib::common.page_size'), trans('ilib::common.page_size_hint')) !!}
            </div>
        </div>
    </div>
</div>