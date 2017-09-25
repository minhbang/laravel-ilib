<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Reader\ReaderEbookRequest;
use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\ILib\Reader\Reader;
use Request;
use DataTables;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Minhbang\ILib\Reader\ReaderEbookTransformer;

/**
 * Class ReaderEbookController
 * Phân quyền tạm thời reader được phép đọc 1 ebook nào đó
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class ReaderEbookController extends BaseController {
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';

    public $route_prefix = 'ilib.';

    /**
     *
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     *
     * @return \Illuminate\View\View
     */
    public function index( Builder $builder ) {
        Reader::removeExpired();
        $this->buildHeading(
            [ trans( 'common.manage' ), trans( 'ilib::reader.allow_ebooks' ) ],
            'fa-file-pdf-o',
            [
                route( $this->route_prefix . 'backend.reader.index' ) => trans( 'ilib::reader.reader' ),
                '#'                                                   => trans( 'ilib::reader.allowed' ),
            ]
        );

        $builder->ajax( route( $this->route_prefix . 'backend.reader_ebook.data' ) );
        $html = $builder->columns( [
            [ 'data' => 'id', 'name' => 'ebook_reader.reader_id', 'title' => 'ID', 'class' => 'min-width text-right' ],
            [ 'data' => 'code', 'name' => 'readers.code', 'title' => trans( 'ilib::reader.code_th' ), 'class' => 'min-width text-center' ],
            [
                'data'  => 'name',
                'name'  => 'users.name',
                'title' => trans( 'user::user.name' ),
                'class' => 'min-width',
            ],
            [
                'data'  => 'ebook',
                'name'  => 'ebooks.title',
                'title' => trans( 'ebook::common.ebook' ),
            ],
            [
                'data'  => 'expires_at',
                'name'  => 'ebook_reader.expires_at',
                'title' => trans( 'ilib::reader.expires_at' ),
                'class' => 'min-width',
            ],
        ] )->addAction( [
            'data'  => 'actions',
            'name'  => 'actions',
            'title' => trans( 'common.actions' ),
            'class' => 'min-width',
        ] );
        $isPTTV = user_is( 'thu_vien.phu_trach' );
        $readers = $isPTTV ? Reader::forSelectize()->get()->all() : [];
        $ebooks = $isPTTV ? Ebook::forSelectize()->orderUpdated()->get()->all() : [];

        return view( 'ilib::backend.reader.ebook', compact( 'html', 'readers', 'ebooks', 'isPTTV' ) );
    }

    /**
     * Danh sách Reader theo định dạng của Datatables.
     *
     * @return mixed
     */
    public function data() {
        /** @var \Minhbang\ILib\Reader\Reader $query */
        $query = Reader::queryDefault()->allowedEbook()->withUser();
        if ( Request::has( 'search_form' ) ) {
            $query = $query
                ->searchWhereBetween( 'ebook_reader.expires_at', 'mb_date_vn2mysql' );
        }

        return DataTables::of( $query )->setTransformer( new ReaderEbookTransformer( $this->route_prefix . 'backend' ) )->make( true );
    }

    /**
     * @param \Minhbang\ILib\Reader\ReaderEbookRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( ReaderEbookRequest $request ) {
        $error = user_is( 'thu_vien.phu_trach' ) ? false : trans( "ilib::reader.add_allow_ebooks_unabled" );
        if ( ! $error ) {
            Reader::removeExpired();
            $inputs = $request->all();
            /** @var \Minhbang\ILib\Reader\Reader $reader */
            $reader = Reader::findOrFail( $inputs['reader_id'] );
            if ( Ebook::where( 'id', $inputs['ebook_id'] )->exists() ) {
                if ( $reader->ebooks->contains( (int) $inputs['ebook_id'] ) ) {
                    $error = trans( "ilib::reader.add_allow_ebooks_reader_exists" );
                } else {
                    $reader->ebooks()->attach( $inputs['ebook_id'], [ 'expires_at' => $this->buildExpiresAt( $inputs['expires_at'] ) ] );
                }
            } else {
                $error = trans( "ilib::reader.add_allow_ebooks_not_found" );
            }
        }

        return response()->json(
            [
                'type'    => $error ? 'error' : 'success',
                'content' => $error ?: trans( "ilib::reader.add_allow_ebooks_success" ),
            ]
        );
    }

    /**
     * @param \Minhbang\ILib\Reader\Reader $reader
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Reader $reader, Ebook $ebook ) {
        if ( user_is( 'thu_vien.phu_trach' ) ) {
            $reader->ebooks()->detach( $ebook );

            return response()->json(
                [
                    'type'    => 'success',
                    'content' => trans( 'common.delete_object_success', [ 'name' => trans( 'ilib::reader.allowed' ) ] ),
                ]
            );
        } else {
            return response()->json(
                [
                    'type'    => 'error',
                    'content' => trans( 'ilib::reader.add_allow_ebooks_unable_delete' ),
                ]
            );
        }
    }

    /**
     * @param \Minhbang\ILib\Reader\Reader $reader
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate( Reader $reader, Ebook $ebook ) {
        $attr = Request::get( '_attr' );
        if ( user_is( 'thu_vien.phu_trach' ) && in_array( $attr, [ 'expires_at' ] ) ) {
            $value = Request::get( '_value' );
            switch ( $attr ) {
                case 'expires_at':
                    $reader->ebooks()->updateExistingPivot( $ebook->id, [ $attr => $this->buildExpiresAt( $value ) ] );
                    break;
                default:
                    break;
            }

            return response()->json(
                [
                    'type'    => 'success',
                    'message' => trans( 'common.quick_update_success', [ 'attribute' => trans( "ilib::reader.{$attr}" ) ] ),
                ]
            );
        } else {
            return response()->json( [ 'type' => 'error', 'message' => 'Invalid quick update request!' ] );
        }
    }

    /**
     * @param string $datetime_vn
     *
     * @return string
     */
    protected function buildExpiresAt( $datetime_vn ) {
        // chuyển ngày giờ định dạng MySQL, thêm phần giây :00 ở cuối
        return mb_date_vn2mysql( $datetime_vn, true, ' ' ) . ':00';
    }
}
