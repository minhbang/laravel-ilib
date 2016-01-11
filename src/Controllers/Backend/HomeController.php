<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\LaravelKit\Extensions\BackendController;

/**
 * Class HomeController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class HomeController extends BackendController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('ilib::backend.index');
    }
}
