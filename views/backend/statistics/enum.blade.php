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
                    <th>{{$type_title}}</th>
                    <th class="min-width text-center">{{trans('ilib::common.ebooks_count')}}</th>
                </tr>
                <?php $total = 0; ?>
                @foreach($couters as $i => $counter)
                    <tr>
                        <td class="min-width text-right">{{$i+1}}</td>
                        <td class="text-success">{{$counter->title}}</td>
                        <td class="min-width text-center">{{$counter->enum_count}}</td>
                    </tr>
                    <?php $total += $counter->enum_count; ?>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right text-uppercase"><strong>{{trans('common.total')}}</strong></td>
                    <td class="min-width text-center"><strong>{{$total}}</strong></td>
                </tr>
            </table>
        </div>
    </div>
@stop