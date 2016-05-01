<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\ILib\Widgets\EbookWidget;
use Minhbang\Ebook\Ebook;

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
        $ebook_widget = new EbookWidget();
        $query = Ebook::queryDefault()->published()->withEnumTitles()->withCategoryTitle()->orderUpdated();
        $query1 = clone $query;
        $ebook_latest = $query->take(6)->get();
        $ebook_featured = $query1->featured()->get();

        return view('ilib::frontend.index', compact('ebook_widget', 'ebook_latest', 'ebook_featured'));
    }
}
