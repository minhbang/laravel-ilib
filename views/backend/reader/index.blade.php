@extends($layout)
@section('content')
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
            @if(!$isPTTV)
                <div class="alert alert-warning"><em>{!! trans('ilib::reader.add_user_notice') !!}</em></div>
            @endif
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="ibox ibox-table">
                <div class="ibox-title">
                    <h5>{!! trans('ilib::reader.manage_title') !!}</h5>
                    <div class="buttons">
                        {!! Html::linkButton('#', trans('common.filter'), ['class'=>'advanced_filter_collapse','type'=>'info', 'size'=>'xs', 'icon' => 'filter']) !!}
                        {!! Html::linkButton('#', trans('common.all'), ['class'=>'advanced_filter_clear', 'type'=>'warning', 'size'=>'xs', 'icon' => 'list']) !!}
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="bg-warning dataTables_advanced_filter hidden">
                        <form class="form-horizontal" role="form">
                            {!! Form::hidden('filter_form', 1) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('filter_created_at', trans('common.created_at'), ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-9">
                                            {!! Form::daterange('filter_created_at', [], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('filter_updated_at', trans('common.updated_at'), ['class' => 'col-md-3 control-label']) !!}
                                        <div class="col-md-9">
                                            {!! Form::daterange('filter_updated_at', [], ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {!! $html->table(['id' => 'reader-manage', 'class' => 'table table-striped table-bordered table-readers']) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    window.datatableDrawCallback = function (dataTableApi) {
        dataTableApi.$('a.quick-update').quickUpdate({
            'url': '{{ route($route_prefix. 'backend.reader.quick_update', ['reader' => '__ID__']) }}',
            'container': '#reader-manage',
            'elementTemplate': {
                '.a-security_id': '{!! Form::select('_value', $securities, null, ['prompt'=>trans('ilib::reader.security_id').'...', 'class' => 'form-control _value']) !!}'
            },
            'dataTableApi': dataTableApi,
            'afterShow': function (element, form) {
                if ($(element).hasClass('a-security_id')) {
                    $('._value', form).selectize();
                }
            }
        });
    };
    window.settings.mbDatatables = {
        trans: {
            name: '{{trans('ilib::reader.reader')}}'
        }
    }
</script>
{!! $html->scripts() !!}

<script type="text/javascript">
    var
        reader_manage = window.LaravelDataTables['reader-manage'],
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
            data: {_token: window.Laravel.csrfToken, code: code, user_id: user, security_id: security},
            dataType: 'json',
            success: function (data) {
                $.fn.mbHelpers.showMessage(data.type, data.content);
                reader_manage.ajax.reload();
                clearForm();
            },
            error: function () {
                $.fn.mbHelpers.showMessage('error', '{{trans('ilib::reader.add_user_error')}}');
            }
        });
    });
</script>
@endpush