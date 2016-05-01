<?php
namespace Minhbang\ILib;

use Minhbang\Option\Option;

/**
 * Class DisplayOption
 *
 * @package Minhbang\ILib
 */
class DisplayOption extends Option
{
    /**
     * @param string $group
     *
     * @return array
     */
    protected function config($group)
    {
        return config("ilib.options.{$group}", []);
    }
    /**
     * @return array
     */
    protected function all()
    {
        return [
            'sort'      => [
                'name.asc'     => trans('common.sort_name') . trans('common.sort_str_asc'),
                'name.desc'    => trans('common.sort_name') . trans('common.sort_str_desc'),
                'pyear.asc'    => trans('ilib::common.sort_pyear') . trans('common.sort_time_asc'),
                'pyear.desc'   => trans('ilib::common.sort_pyear') . trans('common.sort_time_desc'),
                'updated.asc'  => trans('common.sort_updated') . trans('common.sort_time_asc'),
                'updated.desc' => trans('common.sort_updated') . trans('common.sort_time_desc'),
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
            'sort'      => '/^(name|updated|pyear)\.(asc|desc)$/',
            'page_size' => '/^[\d]+$/',
            'type'      => '/^(th|list)$/',
        ];
    }
}