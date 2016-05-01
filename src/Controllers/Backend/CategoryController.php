<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Category\Controller;
use Minhbang\Category\Category;
use Minhbang\Ebook\Ebook;

/**
 * Class CategoryController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->type = Ebook::class;
        Category::$use_moderator = false;
        $this->views['index'] = 'ilib::backend.category';
        parent::__construct();
    }
}
