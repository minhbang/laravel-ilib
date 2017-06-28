<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\ILib\Reader\ReaderRequest;
use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\ILib\Reader\Reader;
use Datatables;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Request;
use Minhbang\User\User;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Minhbang\ILib\Reader\ReaderTransformer;

/**
 * Class ReaderController
 * Quản lý bạn đọc
 * - Chọn User từ Hệ thống, cấp phép thành bạn đọc
 * - Gán quyền được đọc loại Tài liệu ở mức Bảo mật nào (không, mật,...)
 * - Gán quyền tạm thời được đọc 1 tài liệu nào đó
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class ReaderController extends BaseController {
    use QuickUpdateActions;
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';

    public $route_prefix = 'ilib.';

    /**
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Builder $builder ) {
        $name = trans( 'ilib::reader.reader' );
        $this->buildHeading( [ trans( 'common.manage' ), $name ], 'fa-file-pdf-o', [ '#' => $name ] );

        $builder->ajax( route( $this->route_prefix . 'backend.reader.data' ) );
        $html = $builder->columns( [
            [ 'data' => 'id', 'name' => 'user_id', 'title' => 'ID', 'class' => 'min-width text-right' ],
            [ 'data' => 'code', 'name' => 'code', 'title' => trans( 'ilib::reader.code_th' ), 'class' => 'min-width text-center' ],
            [
                'data'  => 'name',
                'name'  => 'users.name',
                'title' => trans( 'user::user.name' ),
            ],
            [ 'data' => 'security_id', 'name' => 'securities.title', 'title' => trans( 'ilib::reader.security_id' ), 'class' => 'min-width' ],
        ] )->addAction( [
            'data'  => 'actions',
            'name'  => 'actions',
            'title' => trans( 'common.actions' ),
            'class' => 'min-width',
        ] );
        $isPTTV = user_is( 'thu_vien.phu_trach' );
        // 10 users chưa phải là reader
        $selectize_users = User::forSelectize( Reader::all()->pluck( 'user_id' ), 10 )->get()->all();
        $securities = Reader::allSecurity();

        return view( 'ilib::backend.reader.index', compact( 'html', 'selectize_users', 'isPTTV', 'securities' ) );
    }


    /**
     * Danh sách Reader theo định dạng của Datatables.
     */
    public function data() {
        /** @var \Minhbang\ILib\Reader\Reader $query */
        $query = Reader::queryDefault()->withUser()->withEnumTitles()->orderUpdated();
        if ( Request::has( 'search_form' ) ) {
            $query = $query
                ->searchWhereBetween( 'readers.created_at', 'mb_date_vn2mysql' )
                ->searchWhereBetween( 'readers.updated_at', 'mb_date_vn2mysql' );
        }

        return Datatables::of( $query )->setTransformer( new ReaderTransformer( $this->route_prefix . 'backend' ) )->make( true );
    }

    /**
     * @param \Minhbang\ILib\Reader\ReaderRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( ReaderRequest $request ) {
        if ( Reader::where( 'user_id', $request->get( 'user_id', 0 ) )->exists() ) {
            return response()->json(
                [
                    'type'    => 'error',
                    'content' => trans( "ilib::reader.user_exists_eror" ),
                ]
            );
        } else {
            $reader = new Reader();
            $reader->fill( $request->all() );
            $type = isset( Reader::allSecurity()[(int) $reader->security_id] ) ? 'success' : 'error';
            if ( $type == 'success' ) {
                $reader->save();
            }

            return response()->json(
                [
                    'type'    => $type,
                    'content' => trans( "ilib::reader.add_user_{$type}" ),
                ]
            );
        }
    }

    /**
     * @param \Minhbang\ILib\Reader\Reader $reader
     *
     * @return \Illuminate\View\View
     */
    public function show( Reader $reader ) {
        return view( 'ilib::backend.reader.show', compact( 'reader' ) );
    }

    /**
     * @param \Minhbang\ILib\Reader\Reader $reader
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Reader $reader ) {
        $type = isset( Reader::allSecurity()[(int) $reader->security_id] ) ? 'success' : 'error';
        if ( $type == 'success' ) {
            $reader->delete();
        }

        return response()->json(
            [
                'type'    => $type,
                'content' => $type == 'success' ?
                    trans( 'common.delete_object_success', [ 'name' => trans( 'ilib::reader.reader' ) ] ) :
                    trans( 'ilib::reader.delete_user_error' ),
            ]
        );
    }

    /**
     * Lấy danh sách readers sử dụng cho selectize_user
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select( $username ) {
        return response()->json(
            Reader::forSelectize()->findText( 'users.username', $username )->get()->all()
        );
    }

    /**
     * Các attributes cho phép quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes() {
        $attributes = [
            'code' => [
                'rules' => 'required|max:20',
                'label' => trans( 'ilib::reader.code' ),
            ],
        ];
        if ( user_is( 'thu_vien.phu_trach' ) ) {
            $attributes['security_id'] = [
                'rules' => 'required|max:255',
                'label' => trans( 'ilib::reader.security_id' ),
            ];
        }

        return $attributes;
    }
}
