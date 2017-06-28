<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\BackendController as BaseController;
use Minhbang\Ebook\Ebook;

/**
 * Class EbookController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class EbookController extends BaseController {
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';
    /**
     * @var string
     */
    public $route_prefix = 'ilib.';

    //public $allStatus = false;

    /**
     * Lấy danh sách ebooks sử dụng cho selectize ebooks
     *
     * @param string $title
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select( $title ) {
        return response()->json(
            Ebook::forSelectize()->orderUpdated()->findText( 'title', $title )->get()->all()
        );
    }

    // TODO chưa sử dụng editUp và statusUp

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\View\View
     */
    public function editUp( Ebook $ebook ) {
        if ( $ebook->attemptUpStatus( 'update' ) ) {
            $ebook->save();

            return parent::edit( $ebook );
        } else {
            return view( 'message', [
                'module'  => trans( 'ilib::common.ilib' ),
                'type'    => 'danger',
                'content' => trans( 'ilib::common.messages.unable_update' ),
            ] );
        }
    }

    /**
     * Thay đổi trạng thái ebook theo qui trình
     *
     * @param Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUp( Ebook $ebook ) {
        $result = $ebook->isReady( 'update' ) && $ebook->updateUpStatus() ? 'success' : 'error';

        return response()->json( [ 'type' => $result, 'content' => trans( "common.status_{$result}" ) ] );
    }

    /**
     * @return array
     */
    protected function getSelectizeStatuses() {
        $statuses = parent::getSelectizeStatuses();

        return user_is( 'thu_vien.phu_trach' ) ? $statuses : array_only( $statuses, [ 'editing', 'pending' ] );
    }
}