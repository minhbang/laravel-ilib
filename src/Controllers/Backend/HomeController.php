<?php namespace Minhbang\ILib\Controllers\Backend;

use Status;
use Minhbang\Ebook\Ebook;
use Minhbang\Kit\Extensions\BackendController;

/**
 * Class HomeController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class HomeController extends BackendController {
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $statuses = Status::of( Ebook::class )->all();
        $counters = [];
        foreach ( $statuses as $status => $info ) {
            $counters[] = [
                'status' => $status,
                'title'  => $info['title'],
                'color'  => $info['color'],
                'count'  => Ebook::status( $status )->count(),
            ];
        }

        $latest_ebooks = Ebook::queryDefault()->withEnumTitles()->latest()->take( 5 )->get();

        return view( 'ilib::backend.index', compact( 'counters', 'latest_ebooks' ) );
    }
}
