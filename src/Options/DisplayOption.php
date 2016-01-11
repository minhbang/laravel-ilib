<?php
namespace Minhbang\ILib\Options;

/**
 * Class DisplayOption
 *
 * @package Minhbang\ILib\Options
 */
class DisplayOption extends Option
{
    /**
     * @return array
     */
    protected function all()
    {
        return [
            'sort'      => [
                'name.asc'     => trans('ilib::common.sort_name') . trans('ilib::common.sort_str_asc'),
                'name.desc'    => trans('ilib::common.sort_name') . trans('ilib::common.sort_str_desc'),
                'pyear.asc'    => trans('ilib::common.sort_pyear') . trans('ilib::common.sort_time_asc'),
                'pyear.desc'   => trans('ilib::common.sort_pyear') . trans('ilib::common.sort_time_desc'),
                'updated.asc'  => trans('ilib::common.sort_updated') . trans('ilib::common.sort_time_asc'),
                'updated.desc' => trans('ilib::common.sort_updated') . trans('ilib::common.sort_time_desc'),
            ],
            'page_size' => [6 => 6, 12 => 12, 30 => 30, 60 => 60],
        ];
    }

    /**
     * Chuyển đổi từ giá trị của option 'sort' thành tên column
     *
     * @return array
     */
    protected function columns()
    {
        return [
            'sort' => [
                'name'    => 'ebooks.title',
                'updated' => 'ebooks.updated_at',
                'pyear'   => 'ebooks.pyear',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function rules()
    {
        return [
            'sort'      => '/^([a-z0-9]+)\.(asc|desc)$/',
            'page_size' => '/^[\d]+$/',
            'type'      => '/^(th|list)$/',
        ];
    }
}