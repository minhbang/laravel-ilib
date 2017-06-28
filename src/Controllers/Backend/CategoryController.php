<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Category\Controller;
use Minhbang\Category\Category;
use Minhbang\Ebook\Ebook;
use Kit;

/**
 * Class CategoryController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class CategoryController extends Controller {
    protected $moderator = false;
    public $route_prefix = 'ilib.';
    protected $layout = 'ilib::layouts.backend';

    public function __construct() {
        $this->type = Kit::alias( Ebook::class );
        Category::$use_moderator = false;
        $this->views['index'] = 'ilib::backend.category';
        parent::__construct();
    }
}
