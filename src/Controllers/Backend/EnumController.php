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

    /**
     * EnumController constructor.
     */
    public function __construct()
    {
        Enum::onlyResources([Ebook::class]);
        parent::__construct();
    }
}
