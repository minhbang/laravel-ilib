@extends('ilib::layouts.backend')
<?php
/** @var \Minhbang\Ebook\Ebook[] $latest_ebooks */
?>
@section('content')
    <div class="row">
        @foreach($counters as $counter)
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="{{route('ilib.backend.ebook.index_status', ['status' => $counter['status']])}}">
                    <div class="widget style1 {{$counter['color']}}-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> {{$counter['title']}} </span>
                                <h2 class="font-bold">{{$counter['count']}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{trans('ilib::common.latest')}}</h5>
                </div>
                <div class="ibox-content">
                    <div class="feed-activity-list dashboard-ebooks">
                        @foreach($latest_ebooks as $i => $ebook)
                            <div class="feed-element">
                                <div class="pull-left">
                                    {!! $ebook->present()->featured_image('', true, false, '_sm') !!}
                                </div>
                                <div class="media-body ">
                                    <small class="pull-right">{{$ebook->updated_at->diffForHumans()}}</small>
                                    {!! $ebook->present()->title_block_1 !!}
                                    {!! $ebook->present()->security !!}
                                    {!! $ebook->present()->status !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{{trans('ilib::common.statistics_user_ebooks')}}</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th class="min-width text-right">#</th>
                            <th>User</th>
                            <th class="min-width text-center">{{trans('ilib::common.ebooks_count')}}</th>
                        </tr>
                        <?php $total = 0; ?>
                        @foreach($user_ebooks as $i => $info)
                            <tr>
                                <td class="min-width text-right">{{$i+1}}</td>
                                <td><strong>{{$info->name}}</strong> - <span class="text-success">{{$info->username}}</span></td>
                                <td class="min-width text-center">{{$info->ebook_count}}</td>
                            </tr>
                            <?php $total += $info->ebook_count; ?>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right text-uppercase"><strong>{{trans('common.total')}}</strong></td>
                            <td class="min-width text-center"><strong>{{$total}}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop