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
                    {!! trans('ilib::reader.add_user') !!}
                </div>
                <div class="panel-body">
                    <form class="form-horizontal-1">
                        <div class="row">
                            <div class="col-lg-6 col-sm-3">
                                {!! Form::select('user_id', [], null, ['id' => 'user_id', 'class' => 'form-control select-user', 'placeholder' => trans('ilib::reader.user_id').'...']) !!}
                            </div>
                            <div class="col-lg-6 col-sm-3">
                                {!! Form::text('user_code', null, ['id' => 'user_code', 'class' => 'form-control', 'placeholder' => trans('ilib::reader.code').'...']) !!}
                            </div>
                            <div class="col-lg-6 col-sm-3">
                                {!! Form::select('security_id', $securities, null, ['id' => 'security_id', 'prompt'=>trans('ilib::reader.security_id').'...', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-lg-12 col-sm-3">
                                <a id="add-user" href="{{route($route_prefix.'backend.reader.store')}}" class="btn
                                btn-success btn-block disabled">
                                    <i class="fa fa-plus"></i> {{trans('common.add')}}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="ibox ibox-table">
                <div class="ibox-title">
                    <h5>{!! trans('ilib::reader.manage_title') !!}</h5>
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
            'name' => trans('ilib::reader.reader'),
            'data_url' => route($route_prefix.'backend.reader.data'),
            'drawCallback' => 'window.datatableDrawCallback'
        ]
    )
    <script type="text/javascript">
        function datatableDrawCallback(oTable) {
            oTable.find('a.quick-update').quickUpdate({
                url: '{{ route($route_prefix.'backend.reader.quick_update', ['reader' => '__ID__']) }}',
                container: '#reader-manage',
                elementTemplate: {
                    '.a-security_id': '{!! Form::select('_value', $securities, null, ['prompt'=>trans('ilib::reader.security_id').'...', 'class' => 'form-control _value']) !!}'
                },
                dataTable: oTable,
                afterShow: function (element, form) {
                    if ($(element).hasClass('a-security_id')) {
                        $('._value', form).selectize();
                    }
                }
            });
        }
        var
            reader_manage = $('#reader-manage'),
            user_code = $('#user_code'),
            user_id = $('#user_id'),
            security_id = $('#security_id'),
            add_user = $('#add-user'),
            form = add_user.closest('form');

        function updateAddUserBtn() {
            if (user_code.val() && user_id.val() && security_id.val()) {
                add_user.removeClass('disabled');
            } else {
                add_user.addClass('disabled');
            }
        }

        function clearForm() {
            form.find("input[type=text]").val("");
            $.each(form.find('select'), function (i, s) {
                s.selectize.clear(true);
            });
        }

        user_code.change(function () {
            updateAddUserBtn();
        });
        security_id.selectize({
            onChange: function () {
                updateAddUserBtn();
            }
        });

        user_id.selectize_user({
            url: '{!! route('backend.user.select', ['query' => '__QUERY__']) !!}',
            users: {!! json_encode($selectize_users) !!},
            onChange: function () {
                updateAddUserBtn();
            }
        });

        add_user.click(function (e) {
            e.preventDefault();
            var
                code = user_code.val(),
                user = user_id.val(),
                security = security_id.val();
            if (add_user.hasClass('disabled') || user <= 0 || security <= 0 || code.length <= 0) {
                return;
            }
            $.ajax({
                type: 'post',
                url: add_user.attr('href'),
                data: {_token: window.csrf_token, code: code, user_id: user, security_id: security},
                dataType: 'json',
                success: function (data) {
                    $.fn.mbHelpers.showMessage(data.type, data.content);
                    reader_manage.dataTable().fnReloadAjax();
                    clearForm();
                },
                error: function () {
                    $.fn.mbHelpers.showMessage('error', '{{trans('ilib::reader.add_user_error')}}');
                }
            });
        });
    </script>

@stop