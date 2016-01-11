<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Category\Controller;
use Minhbang\Category\Item;
/**
 * Class CategoryController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class CategoryController extends Controller
{
    protected $type = 'ebook';

    public function __construct()
    {
        Item::$use_moderator = false;
        $this->views['index'] = 'ilib::backend.category';
        parent::__construct();
    }
}
