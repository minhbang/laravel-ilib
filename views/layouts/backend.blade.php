@extends('backend.layouts.master')

@section('skin', 'skin-4')

@section('module-name', Html::twoPart(trans('ilib::common.cpanel'), 'text-danger', false, '|'))
@section('user-name', RoleManager::getUserMaxRole(['sys.sadmin', 'tv.pt', 'tv.nv']))

@section('sidebar')
    {!! MenuManager::render(
        'ilib_backend.sidebar',
        [
            'header' => '<div class="logo"><div class="app-name">'.trans('ilib::common.ilib').'</div></div><div class="logo-element"><div class="logo-small"></div></div>'
        ]
    )!!}
@endsection