<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\Ebook;
use Minhbang\Kit\Extensions\BackendController;

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
        $ebook = new Ebook();
        $statuses = $ebook->statuses();
        $colors = ['white', 'yellow', 'red', 'navy'];
        $counters = [];
        foreach ($statuses as $status => $title) {
            $counters[] = [
                'status' => $status,
                'title'  => $title,
                'color'  => $colors[$status],
                'count'  => Ebook::status($status)->count(),
            ];
        }

        $latest_ebooks = Ebook::queryDefault()->where('status', '>', Ebook::STATUS_UPLOADED)->withEnumTitles()
            ->latest()->take(5)->get();

        return view('ilib::backend.index', compact('counters', 'latest_ebooks'));
    }
}
