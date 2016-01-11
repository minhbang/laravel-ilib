<?php
namespace Minhbang\ILib\Controllers\Frontend;

/**
 * Class HomeController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('ilib::frontend.index');
    }
}
