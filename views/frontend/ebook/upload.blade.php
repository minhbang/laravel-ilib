@extends('kit::backend.layouts.basic')

@section('heading', trans('ilib::common.ilib') .' / '. trans('ilib::common.upload'))

@section('classes', 'col-md-6 col-md-offset-3')

@section('content')
    {!! Form::model($ebook, ['files' => true]) !!}
    <div class="ibox">
        <div class="ibox-title">
            <h5>{!! trans('ilib::common.upload_file_title') !!}</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group{{ $errors->has("title") ? ' has-error':'' }}">
                {!! Form::label("title", trans('ebook::common.title'), ['class' => "control-label"]) !!}
                {!! Form::text("title", null, ['class' => 'form-control']) !!}
                @if($errors->has("title"))
                    <p class="help-block">{{ $errors->first("title") }}</p>
                @endif
            </div>
            <div class="form-group{{ $errors->has("name") ? ' has-error':'' }}">
                {!! Form::label("name", trans('file::common.name'), ['class' => "control-label"]) !!}
                {!! Form::fileinput("name", ['prompt'=>trans('ilib::common.upload_file_hint')]) !!}
                @if($errors->has("name"))
                    <p class="help-block">{{ $errors->first("name") }}</p>
                @endif
            </div>

            <div class="form-group{{ $errors->has("summary") ? ' has-error':'' }}">
                {!! Form::label("summary", trans('ebook::common.summary'), ['class' => "control-label"]) !!}
                {!! Form::textarea("summary", null, [
                    'class' => 'form-control wysiwyg',
                    'data-editor' => 'mini',
                    'data-height' => 500,
                    'data-attribute' => 'summary',
                    'data-resource' => 'ebook',
                    'data-id' => $ebook->id
                ]) !!}
                @if($errors->has("summary"))
                    <p class="help-block">{{ $errors->first("summary") }}</p>
                @endif
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success save" style="margin-right: 15px;">{{ trans('common.upload') }}</button>
                <a href="{{ route('ilib.index') }}" class="btn btn-white">{{ trans('common.cancel') }}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.wysiwyg').mbEditor({});
        });
    </script>
@endpush
