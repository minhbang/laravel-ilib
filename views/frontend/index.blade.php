@extends('ilib::layouts.frontend')
@section('content')
    <div class="ilib-home">
        {!! $ebook_widget->bxSlider($ebook_featured) !!}
        <div class="main-heading2">{{trans('ilib::common.latest')}}</div>
        <div class="ebooks">
            {!! $ebook_widget->items($ebook_latest, 'th') !!}
        </div>
    </div>
@stop

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ebook-slider').bxSlider({
                auto: true
            });
        });
    </script>
@endpush