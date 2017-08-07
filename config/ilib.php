<?php
return [
    'middlewares' => [
        'backend' => ['web', 'role:thu_vien.*'],
        'frontend' => ['web'],
    ],
    /**
     * Default display options
     */
    'options' => [
        'category' => [
            'sort' => 'name.asc',
            'page_size' => 6,
            'type' => 'th',
        ],
        'search' => [
            'sort' => 'name.asc',
            'page_size' => 6,
            'type' => 'th',
        ],
    ],
    'sidebarGroups' => [
        'ilib' => [
            'title' => 'trans::ilib::layout.groups.ilib',
            'description' => 'trans::ilib::layout.groups.ilib_description',
        ],
    ],
    'sidebars' => [
        'ilib_main' => [
            'title' => 'trans::ilib::layout.sidebars.ilib_main.title',
            'description' => 'trans::ilib::layout.sidebars.ilib_main.description',
            'group' => 'ilib',
        ],
        'ilib_sidebar' => [
            'title' => 'trans::ilib::layout.sidebars.ilib_sidebar.title',
            'description' => 'trans::ilib::layout.sidebars.ilib_sidebar.description',
            'group' => 'ilib',
        ],
    ],

    // Cấu hình menu cho module ilib
    'menu' => [
        // Định nghĩa menu zones cho ilib
        'zones' => [
            'ilib_backend' => [
                'sidebar' => [
                    'items' => [
                        'dashboard' => [
                            'url' => 'route:ilib.backend.dashboard',
                            'label' => 'trans:backend.dashboard',
                            'icon' => 'dashboard',
                            'class' => 'special_link',
                        ],
                        'home' => [
                            'url' => 'route:ilib.index',
                            'label' => 'trans:common.home',
                            'icon' => 'home',
                            'attributes' => ['target' => '_blank'],
                        ],
                        'content' => ['label' => 'trans:menu::common.items.content', 'icon' => 'fa-files-o'],
                        'user' => ['label' => 'trans:ilib::reader.manage', 'icon' => 'fa-users'],
                        'statistic' => [
                            'label' => 'trans:ilib::common.statistics',
                            'icon' => 'fa-bar-chart',
                            'active' => 'ilib/backend/statistics*',
                        ],
                        'setting' => ['label' => 'trans:menu::common.items.setting', 'icon' => 'fa-cogs'],
                        'maintenance' => ['label' => 'trans:menu::common.items.maintenance', 'icon' => 'fa-wrench'],
                    ],
                    'presenter' => 'metis',
                    'options' => [
                        'attributes' => ['id' => 'side-menu'],
                    ],
                ],
            ],
        ],
        'presenters' => [],
        'types' => [],
        // Định nghĩa menus cho ilib
        'menus' => [
            // Backend
            'backend.sidebar.appearance.widget_ilib' => [
                'priority' => 3,
                'url' => 'route:backend.widget.index|group:ilib',
                'label' => 'trans:ilib::layout.groups.ilib',
                'icon' => 'fa-puzzle-piece',
                'active' => 'backend/widget/index/ilib*',
            ],
            // iLib Backend
            'ilib_backend.sidebar.content.category' => [
                'priority' => 1,
                'url' => 'route:ilib.backend.category.index',
                'label' => 'trans:category::common.category',
                'icon' => 'fa-sitemap',
                'active' => 'ilib/backend/category*',
            ],
            'ilib_backend.sidebar.content.ebook' => [
                'priority' => 2,
                'url' => 'route:ilib.backend.ebook.index',
                'label' => 'trans:ebook::common.ebooks',
                'icon' => 'fa-book',
                'active' => 'ilib/backend/ebook*',
            ],
            'ilib_backend.sidebar.user.reader' => [
                'priority' => 1,
                'url' => 'route:ilib.backend.reader.index',
                'label' => 'trans:ilib::reader.reader',
                'icon' => 'fa-id-card-o',
                'active' => ['ilib/backend/reader', 'ilib/backend/reader/*'],
            ],
            'ilib_backend.sidebar.user.reader_ebook' => [
                'priority' => 2,
                'url' => 'route:ilib.backend.reader_ebook.index',
                'label' => 'trans:ilib::reader.allow_ebooks',
                'icon' => 'fa-clock-o',
                'active' => 'ilib/backend/reader_ebook*',
            ],

            'ilib_backend.sidebar.statistic.category' => [
                'url' => 'route:ilib.backend.statistics.category',
                'label' => 'trans:ilib::common.statistics_category',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/category',
            ],
            'ilib_backend.sidebar.statistic.language' => [
                'url' => 'route:ilib.backend.statistics.enum|type:language',
                'label' => 'trans:ilib::common.statistics_language',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/enum/language',
            ],
            'ilib_backend.sidebar.statistic.security' => [
                'url' => 'route:ilib.backend.statistics.enum|type:security',
                'label' => 'trans:ilib::common.statistics_security',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/enum/security',
            ],
            'ilib_backend.sidebar.statistic.writer' => [
                'url' => 'route:ilib.backend.statistics.enum|type:writer',
                'label' => 'trans:ilib::common.statistics_writer',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/enum/writer',
            ],
            'ilib_backend.sidebar.statistic.publisher' => [
                'url' => 'route:ilib.backend.statistics.enum|type:publisher',
                'label' => 'trans:ilib::common.statistics_publisher',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/enum/publisher',
            ],
            'ilib_backend.sidebar.statistic.pplace' => [
                'url' => 'route:ilib.backend.statistics.enum|type:pplace',
                'label' => 'trans:ilib::common.statistics_pplace',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/enum/pplace',
            ],
            'ilib_backend.sidebar.statistic.read' => [
                'url' => 'route:ilib.backend.statistics.read',
                'label' => 'trans:ilib::common.statistics_read',
                'icon' => 'fa-bars',
                'active' => 'ilib/backend/statistics/read',
            ],
            'ilib_backend.sidebar.setting.enum' => [
                'url' => 'route:ilib.backend.enum.index',
                'label' => 'trans:enum::common.enums',
                'icon' => 'fa-list',
                'active' => 'ilib/backend/enum*',
            ],
        ],
    ],

];
