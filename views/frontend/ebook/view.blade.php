@extends('ilib::layouts.frontend-wide')
@php
    /** @var Minhbang\Ebook\Ebook $ebook */
    /** @var Minhbang\File\File $file */
@endphp
@section('content')
    <div class="ebook viewer">
        <div class="main-heading text-primary">
            @if($multi_files)
                {{$ebook->title}}: <span>{!! $file->present()->icon !!} {{$file->title}}</span>
            @else
                {!! $file->present()->icon !!} {{$ebook->title}}
            @endif
            <a href="#" class="full-screen"><i class="fa fa-window-maximize"></i></a>
        </div>
        <div class="content">
            <iframe src="/mbPDFjs/web/viewer.html?file={{route('ilib.ebook.download', ['file' => $file->id, 'ebook' => $ebook->id, 'slug' => $ebook->slug])}}"
                    frameborder="0"></iframe>
        </div>
    </div>
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.full-screen').click(function (e) {
            e.preventDefault();
            $(this).closest('.ebook.viewer').toggleClass('full-screen');
            $('i', this).toggleClass('fa-window-maximize').toggleClass('fa-window-minimize')
        });
    });
</script>
@endpush
