<?php namespace Minhbang\ILib\Controllers\Backend;

use Status;
use Minhbang\Ebook\Ebook;
use Minhbang\Kit\Extensions\BackendController;
use DB;

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

        $latest_ebooks = Ebook::queryDefault()->withEnumTitles()->latest('updated_at')->take( 5 )->get();
        $user_ebooks = DB::table( 'ebooks' )->whereNotIn( 'status', [ 'uploaded' ] )
                         ->leftJoin( 'users', 'users.id', '=', 'ebooks.user_id' )
                         ->select( 'user_id', DB::raw( 'count(*) as ebook_count' ), 'users.name', 'users.username' )
                         ->orderBy('ebook_count', 'desc')
                         ->groupBy( 'user_id' )
                         ->get()->all();

        return view( 'ilib::backend.index', compact( 'counters', 'latest_ebooks', 'user_ebooks' ) );
    }
}
