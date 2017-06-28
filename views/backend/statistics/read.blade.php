@extends($layout)
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! trans('ilib::common.statistics_read_list') !!}</h5>
        </div>
        <div class="ibox-content">
            {!! $html->table(['id' => 'read-manage']) !!}
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    window.settings.mbDatatables = {
        trans: {
            name: '{{trans('ebook::common.ebook')}}'
        }
    }
</script>
{!! $html->scripts() !!}
@endpush