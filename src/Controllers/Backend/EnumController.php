<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\Ebook;
use Minhbang\Enum\Controller as BaseController;
use Enum;

/**
 * Class EnumController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class EnumController extends BaseController {
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';
    /**
     * @var string
     */
    public $route_prefix = 'ilib.';

    /**
     * EnumController constructor.
     */
    public function __construct() {
        Enum::onlyModels( [ Ebook::class ] );
        parent::__construct();
    }

    protected function readOnlyEnumTypes() {
        return user_is( 'thu_vien.phu_trach' ) ? [] : [ 'ebook.security' => trans( 'ilib::common.security_edit_notice' ) ];
    }
}
