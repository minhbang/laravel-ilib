@extends($layout)
@section('content')
    <div id="reader-manage-tools" class="hidden">
        <div class="dataTables_toolbar">
            {!! Html::linkButton('#', trans('common.search'), ['class'=>'advanced_search_collapse','type'=>'info', 'size'=>'xs', 'icon' => 'search']) !!}
            {!! Html::linkButton('#', trans('common.all'), ['class'=>'filter-clear', 'type'=>'warning', 'size'=>'xs', 'icon' => 'list']) !!}
        </div>
        <div class="bg-warning dataTables_advanced_search">
            <form class="form-horizontal" role="form">
                {!! Form::hidden('search_form', 1) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('search_created_at', trans('common.created_at'), ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!! Form::daterange('search_created_at', [], ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('search_updated_at', trans('common.updated_at'), ['class' => 'col-md-3 control-label']) !!}
                            <div class="col-md-9">
                                {!! Form::daterange('search_updated_at', [], ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5 col-md-12">
            <div class="panel panel-green">
                <div class="panel-heading">
                    {!! trans('ilib::reader.add_allow_ebooks') !!}
                </div>
                <div class="panel-body">
                    <div class="form-horizontal-1">
                        <div class="row">
                            <div class="col-lg-12 col-sm-9">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        {!! Form::select('reader_id', [], null, ['id' => 'reader_id', 'class' => 'form-control select-user', 'placeholder' => trans('ilib::reader.reader').'...']) !!}
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        {!! Form::text('expires_at', null, ['id' => 'expires_at', 'class' => 'form-control datetimepicker', 'placeholder' => trans('ilib::reader.expires_at').'...']) !!}
                                    </div>
                                    <div class="col-lg-12 col-sm-12">
                                        {!! Form::select('ebook_id', [], null, ['id' => 'ebook_id', 'prompt'=>trans
                                        ('ebook::common.ebook').'...', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-3">
                                <a id="add-reader" href="{{route($route_prefix.'backend.reader_ebook.store')}}" class="btn
                                btn-success btn-block disabled">
                                    <i class="fa fa-plus"></i> {{trans('common.add')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="ibox ibox-table">
                <div class="ibox-title">
                    <h5>{!! trans('ilib::reader.allow_ebooks_title') !!}</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                    {!! $table->render('_datatable') !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @include(
        '_datatable_script',
        [
            'name' => trans('ilib::reader.allowed'),
            'data_url' => route($route_prefix.'backend.reader_ebook.data'),
            'drawCallback' => 'window.datatableDrawCallback'
        ]
    )
    <script type="text/javascript">
        var datetimepicker_config = $.extend(true, $.fn.mbHelpers.config.datepicker, {
            timepicker: true,
            format: 'd/m/Y H:i'
        });
        function datatableDrawCallback(oTable) {
            oTable.find('a.quick-update').quickUpdate({
                container: '#reader-manage',
                elementTemplate: {
                    '.a-ebook_id': '{!! Form::select('_value', [], null, ['prompt'=>trans('ilib::reader.ebook_id').'...','class' => 'form-control _value']) !!}'
                },
                dataTable: oTable,
                afterShow: function (element, form) {
                    if ($(element).hasClass('a-ebook_id')) {
                        $('._value', form).selectize();
                    }
                    if ($(element).hasClass('a-expires_at')) {
                        $('._value', form).datetimepicker(datetimepicker_config);
                    }
                }
            });
        }
        var
            reader_manage = $('#reader-manage'),
            expires_at = $('#expires_at'),
            reader_id = $('#reader_id'),
            ebook_id = $('#ebook_id'),
            add_reader = $('#add-reader');

        function updateAddReaderBtn() {
            if (expires_at.val() && reader_id.val() && ebook_id.val()) {
                add_reader.removeClass('disabled');
            } else {
                add_reader.addClass('disabled');
            }
        }
        expires_at.change(function () {
            updateAddReaderBtn();
        });


        var ebooks = {!! json_encode($ebooks) !!},
            selected_ebook = ebook_id.find('option:selected').text(),
            select_ebook_url = '{!! route($route_prefix.'backend.ebook.select', ['query' => '__QUERY__']) !!}',
            select_ebook_init = true;
        ebook_id.selectize({
            valueField: 'id',
            labelField: 'title',
            searchField: 'title',
            create: false,
            preload: true,
            load: function (query, callback) {
                var selectize = this;
                if (select_ebook_init && selected_ebook) {
                    query = selected_ebook;
                }
                if (!query.length) {
                    return callback();
                }
                $.ajax({
                    url: select_ebook_url.replace('__QUERY__', encodeURIComponent(query)),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (data) {
                        callback(data);
                        if (select_ebook_init && selected_ebook) {
                            select_ebook_init = false;
                            $(selectize).data('select_ebook_init', false);
                            if (data.length) {
                                selectize.updateOption(ebook_id.val(), data[0]);
                            }
                        }
                    }
                });
            },
            onChange: function () {
                updateAddReaderBtn();
            }
        });
        if (ebooks.length) {
            ebook_id.selectize()[0].selectize.addOption(ebooks);
        }


        reader_id.selectize_user({
            url: '{!! route($route_prefix.'backend.reader.select', ['query' => '__QUERY__']) !!}',
            users: {!! json_encode($readers) !!},
            onChange: function () {
                updateAddReaderBtn();
            }
        });

        add_reader.click(function (e) {
            e.preventDefault();
            var
                expires_at_val = expires_at.val(),
                reader_val = reader_id.val(),
                ebook_val = ebook_id.val();
            if (add_reader.hasClass('disabled') || reader_val <= 0 || ebook_val <= 0 || expires_at_val.length <= 0) {
                return;
            }
            $.ajax({
                type: 'post',
                url: add_reader.attr('href'),
                data: {
                    _token: window.csrf_token,
                    expires_at: expires_at_val,
                    reader_id: reader_val,
                    ebook_id: ebook_val
                },
                dataType: 'json',
                success: function (data) {
                    $.fn.mbHelpers.showMessage(data.type, data.content);
                    reader_manage.dataTable().fnReloadAjax();
                },
                error: function () {
                    $.fn.mbHelpers.showMessage('error', '{{trans('ilib::reader.add_allow_ebooks_error')}}');
                }
            });
        });
    </script>

@stop