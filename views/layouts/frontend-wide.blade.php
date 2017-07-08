@extends('layouts.master')

@section('body-class', 'layout-ilib')

@section('module-name')
    <div class="module-name">{{trans('ilib::common.ilib')}}</div>
@endsection

@section('main')
    @parent
    <div id="content">
        @section('content')
        @show
    </div>
@endsection