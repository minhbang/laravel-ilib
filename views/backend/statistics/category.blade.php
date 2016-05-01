@extends($layout)
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! trans('ilib::common.statistics_list') !!}</h5>
        </div>
        <div class="ibox-content">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th class="min-width text-right">#</th>
                    <th>{{trans('category::common.category')}}</th>
                    <th class="min-width text-center">{{trans('ilib::common.ebooks_count')}}</th>
                </tr>
                @foreach($data as $i => $item)
                    <tr>
                        <td class="min-width text-right">{{$i+1}}</td>
                        <td class="text-success">
                            <span style="padding-left: {{30*($item['depth'] -1)}}px">— {{$item['title']}}</span>
                        </td>
                        <td class="min-width text-center">{{$item['count'] ? $item['count'] : ''}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@stop