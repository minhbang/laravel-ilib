@extends('layouts.master')

@section('body-class', 'layout-ilib')

@section('module-name')
    <div class="module-name">{{trans('ilib::common.ilib')}}</div>
@endsection

@section('main')
    @parent
    <div class="container">
        <div class="row">
            <div id="content" class="col-lg-9 col-sm-8">
                @yield('content')
            </div>
            <div id="sidebar" class="col-lg-3 col-sm-4">
                @section('sidebar')
                    {!! Layout::renderSidebar('ilib_sidebar') !!}
                    <div class="buttons">
                        <a href="{{route('ilib.ebook.upload')}}"
                           class="btn btn-success btn-block">{{trans('ilib::common.upload_ebooks')}}</a>
                        @if(user_is('thu_vien.*'))
                            <a href="{{route('ilib.backend.dashboard')}}"
                               class="btn btn-info btn-block">{{trans('ilib::common.manage')}}</a>
                        @endif
                    </div>
                @show
            </div>
        </div>
    </div>
@endsection