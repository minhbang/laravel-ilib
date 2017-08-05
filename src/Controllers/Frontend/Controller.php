<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Kit\Extensions\Controller as BaseController;
use View;
use CategoryManager;
use Minhbang\Ebook\Ebook;

/**
 * Class Controller
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
abstract class Controller extends BaseController
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //View::share('ebook_category', CategoryManager::of(Ebook::class));
    }

    /**
     * @param array|string $breadcrumbs
     * @param bool $homeItem
     *
     * @return array
     */
    protected function buildBreadcrumbs($breadcrumbs, $homeItem = true)
    {
        $breadcrumbs = [route('ilib.index') => trans('ilib::common.ilib')] + $breadcrumbs;

        return parent::buildBreadcrumbs($breadcrumbs, $homeItem);
    }
}
