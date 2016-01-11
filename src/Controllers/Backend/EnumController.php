<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\Ebook;
use Minhbang\Enum\Controller as BaseController;
use Minhbang\Enum\Enum;

/**
 * Class EnumController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class EnumController extends BaseController
{
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';
    /**
     * @var string
     */
    protected $route_prefix = 'ilib.';

    public function __construct()
    {
        Enum::$resource_classes = [Ebook::class];
        parent::__construct();
    }
}
