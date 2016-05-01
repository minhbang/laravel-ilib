@extends($layout)
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! trans('ilib::common.statistics_read_list') !!}</h5>
        </div>
        <div class="ibox-content">
            {!! $table->render('_datatable') !!}
        </div>
    </div>
@stop

@section('script')
    @include(
        '_datatable_script',
        [
            'name' => trans('ebook::common.ebook'),
            'data_url' => route('ilib.backend.statistics.read_data')
        ]
    )
@stop