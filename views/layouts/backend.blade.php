@extends('backend.layouts.master')

@section('skin', 'ilib-skin')

@section('module-name')
    {!! Html::twoPart(trans('ilib::common.cpanel'), 'text-danger', false, '|') !!}
@endsection
@section('user-name', Authority::user()->firstRole(['sys.sadmin','sys.admin', 'thu_vien.phu_trach', 'thu_vien.nhan_vien'], 'title'))

@section('sidebar')
    {!! MenuManager::render(
        'ilib_backend.sidebar',
        [
            'header' => '<div class="logo"><div class="app-name">'.trans('ilib::common.ilib').'</div></div><div class="logo-element"><div class="logo-small"></div></div>'
        ]
    )!!}
@endsection