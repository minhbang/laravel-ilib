@extends('ilib::layouts.frontend-wide')
@section('content')
    <div class="ebook viewer">
        <div class="main-heading">
            <h3>
                {!! $ebook->present()->fileicon !!} {{$ebook->title}}
                <small class="text-primary"><em>{{$file->title}}</em></small>
                <div class="pull-right"><a class="btn btn-danger btn-sm toggle-fullscreen" href="#"><i
                                class="fa fa-expand"></i></a></div>
            </h3>
        </div>
        <div class="content">
            <iframe src="/viewer/web/viewer.html?file={{route('ilib.ebook.download', ['ebook' => $ebook->id, 'file' => $file->id, 'slug' => $ebook->slug])}}"
                    frameborder="0"></iframe>
        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            var viewer = $('.ebook.viewer');
            $('.toggle-fullscreen').click(function (e) {
                e.preventDefault();
                viewer.toggleClass('fullscreen');
                $(this).find('i').toggleClass('fa-expand').toggleClass('fa-compress');
            });
        });
    </script>
@endsection