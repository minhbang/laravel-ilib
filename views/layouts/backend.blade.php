@extends('backend.layouts.master')

@section('skin', 'skin-4')

@section('module-name', Html::twoPart(trans('ilib::common.cpanel'), 'text-danger', false, '|'))
@section('user-name', RoleManager::getUserMaxRole(['sys.sadmin', 'tv.pt', 'tv.nv']))

@section('sidebar')
    {!! Html::metisMenu(
        [
            [
                'url'   => route('ilib.backend.dashboard'),
                'label' => trans('backend.dashboard'),
                'icon'  => 'dashboard',
                'class' => 'special_link'
            ],
            [
                'url'        => '/',
                'label'      => trans('common.home'),
                'icon'       => 'home',
                'attributes' => ['target' => '_blank']
            ],
            [
                'url'    => '#',
                'label'  => trans('backend.content'),
                'icon'   => 'fa-files-o',
                'active' => ['ilib/backend/category*', 'ilib/backend/ebook*'],
                'items'  => [
                    [
                        'url'    => route('ilib.backend.category.index'),
                        'label'  => trans('category::common.category'),
                        'icon'   => 'fa-sitemap',
                        'active' => 'ilib/backend/category*'
                    ],
                    [
                        'url'    => route('ilib.backend.ebook.index'),
                        'label'  => trans('ebook::common.ebooks'),
                        'icon'   => 'fa-book',
                        'active' => 'ilib/backend/ebook*'
                    ],
                ]
            ],
            [
                'url'    => '#',
                'label'  => trans('ilib::common.users'),
                'icon'   => 'fa-users',
                'active' => ['ilib/backend/reader*'],
                'items'  => [
                    [
                        'url'    => route('ilib.backend.reader.index'),
                        'label'  => trans('ilib::reader.reader'),
                        'icon'   => 'fa-user',
                        'active' => ['backend/reader', 'backend/reader/*']
                    ],
                    [
                        'url'    => route('ilib.backend.reader_ebook.index'),
                        'label'  => trans('ilib::reader.allow_ebooks'),
                        'icon'   => 'fa-legal',
                        'active' => ['backend/reader_ebook*']
                    ],
                ]
            ],
            [
                'url'    => '#',
                'label'  => trans('ilib::common.statistics'),
                'icon'   => 'fa-bar-chart',
                'active' => ['ilib/backend/statistics*'],
                'items'  => [
                    [
                        'url' => route('ilib.backend.statistics.category'),
                        'label' => trans('ilib::common.statistics_category'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/category'
                    ],
                    [
                        'url' => route('ilib.backend.statistics.enum', ['type' => 'language']),
                        'label' => trans('ilib::common.statistics_language'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/enum/language'
                    ],
                    [
                        'url' => route('ilib.backend.statistics.enum', ['type' => 'security']),
                        'label' => trans('ilib::common.statistics_security'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/enum/security'
                    ],
                    [
                        'url' => route('ilib.backend.statistics.enum', ['type' => 'writer']),
                        'label' => trans('ilib::common.statistics_writer'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/enum/writer'
                    ],
                    [
                        'url' => route('ilib.backend.statistics.enum', ['type' => 'publisher']),
                        'label' => trans('ilib::common.statistics_publisher'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/enum/publisher'
                    ],
                    [
                        'url' => route('ilib.backend.statistics.enum', ['type' => 'pplace']),
                        'label' => trans('ilib::common.statistics_pplace'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/enum/pplace'
                    ],
                    [
                        'url' => route('ilib.backend.statistics.read'),
                        'label' => trans('ilib::common.statistics_read'),
                        'icon' => 'fa-bars',
                        'active' => 'ilib/backend/statistics/read'
                    ],
                ]
            ],
            [
                'url'    => '#',
                'label'  => trans('backend.config'),
                'icon'   => 'fa-cogs',
                'active' => ['ilib/backend/enum*'],
                'items'  => [
                    [
                        'url' => route('ilib.backend.enum.index'),
                        'label' => trans('enum::common.enums'),
                        'icon' => 'fa-list',
                        'active' => 'ilib/backend/enum*'
                    ],
                ]
            ],
        ],
        '<div class="logo"><div class="app-name">'.trans('ilib::common.ilib').'</div></div>
            <div class="logo-element"><div class="logo-small"></div></div>',
        'side-menu'
    ) !!}
@endsection