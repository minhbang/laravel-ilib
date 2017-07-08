@extends('layouts.master')

@section('body-class', 'layout-ilib')

@section('module-name')
    <div class="module-name">{{trans('ilib::common.ilib')}}</div>
@endsection

@section('main')
    <div class="row">
        <div id="content" class="col-sm-8">
            @parent
            @yield('content')
        </div>
        <div id="sidebar" class="col-sm-4">
            @if(!Route::currentRouteNamed('ilib.search'))
                <div class="search">
                    {!! Form::open(['route' => 'ilib.search', 'method' => 'get']) !!}
                    <div class="input-group">
                        <input name="q" type="text" class="form-control" placeholder="{{trans('common.keyword')}}...">
                        <span class="input-group-btn">
                            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
            @endif
            @section('sidebar')
                <div class="panel panel-primary">
                    <div class="panel-heading">{{trans('ilib::common.categories')}}</div>
                    <div id="categories-tree"></div>
                </div>
                <div class="buttons">
                    <a href="{{route('ilib.ebook.upload')}}" class="btn btn-success btn-block">{{trans('ilib::common.upload_ebooks')}}</a>
                    @if(user_is('thu_vien.*'))
                        <a href="{{route('ilib.backend.dashboard')}}" class="btn btn-info btn-block">{{trans('ilib::common.manage')}}</a>
                    @endif
                </div>
            @show
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    var category_route = '{{route('ilib.category.show', ['category' => '__ID__'])}}',
        categories_data = {!! $ebook_category->tree( isset($category) ? $category : null) !!};

    $(document).ready(function () {
        var categories_tree = $('#categories-tree');
        categories_tree.treeview({
            data: categories_data,
            levels: 1
        });
        categories_tree.on('click', 'li', function (e) {
            e.preventDefault();
            if ($(e.target).is('.expand-icon')) {
                return;
            }
            window.location.href = category_route.replace('__ID__', $(this).data('id'));
        });
    });
</script>
@endpush