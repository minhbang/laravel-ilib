<?php namespace Minhbang\ILib;

use Minhbang\Status\Managers\NewStatusManager;
use Authority;

/**
 * Class StatusManager
 * Quản lý trạng thái của Ebook, 4 trạng thái
 * - uploaded: Người dùng đóng góp (upload lên)
 * - editing: Đang biên mục (nhân viên thư viện xử lý)
 * - pending: Chờ duyệt (Phụ trách thư viện)
 * - published: Đã duyệt (Phụ trách thư viện)
 *
 * @package Minhbang\ILib
 */
class StatusManager extends NewStatusManager {
    protected $trans_prefix = 'ilib::status.';

    /**
     * Trạng thái mặc định
     *
     * @return string
     */
    public function defaultStatus() {
        return 'uploaded';
    }

    /**
     * Tất cả trạng thái
     *
     * @return array
     */
    protected function allStatuses() {
        return [
            [
                'value'   => 'uploaded',
                'title'   => trans( 'ilib::status.uploaded' ),
                'actions' => [
                    'read|update|delete' => function ( $model, $user ) {
                        /** @var \Minhbang\User\Support\HasOwner $model */
                        return $model && $user && (
                                $model->isOwnedBy( $user ) ||                 // Người upload
                                Authority::user( $user )->isAdmin() ||        // Hoặc Administrator
                                Authority::user( $user )->is( 'thu_vien.*' )  // Hoặc Nhân viên / Phụ trách thư viện
                            );
                    },
                ],
                'up'      => 'editing',
                'up_edit' => true,
                'css'     => 'default',
                'color'   => 'white',
            ],

            [
                'value'   => 'editing',
                'title'   => trans( 'ilib::status.editing' ),
                'actions' => [
                    'read|update|delete' => function ( $model, $user ) {
                        /** @var \Minhbang\User\Support\HasOwner $model */
                        return $model && $user && (
                                $model->isOwnedBy( $user ) ||                 // Người upload
                                Authority::user( $user )->isAdmin() ||        // Là Administrator
                                Authority::user( $user )->is( 'thu_vien.phu_trach' )  // Hoặc Nhân viên / Phụ trách thư viện
                            );
                    },
                ],
                'to_link' => true, // Chuyển trạng thái từ dưới lên -> editUp, false: statusUp
                'down'    => 'uploaded',
                'up'      => 'pending',
                'css'     => 'warning',
                'color'   => 'yellow',
            ],

            [
                'value'   => 'pending',
                'title'   => trans( 'ilib::status.pending' ),
                'actions' => [
                    'read'          => function ( $model, $user ) {
                        /** @var \Minhbang\User\Support\HasOwner $model */
                        return $model && $user && (
                                Authority::user( $user )->isAdmin() || // Là Administrator
                                Authority::user( $user )->is( 'thu_vien.*' )  // Hoặc Nhân viên / Phụ trách thư viện
                            );
                    },
                    'update|delete' => function ( $model, $user ) {
                        /** @var \Minhbang\User\Support\HasOwner $model */
                        return $model && $user && (
                                $model->isOwnedBy( $user ) ||                 // Người upload
                                Authority::user( $user )->isAdmin() || // Là Administrator
                                Authority::user( $user )->is( 'thu_vien.phu_trach' )  // Hoặc Phụ trách thư viện
                            );
                    },
                ],
                'down'    => 'editing',
                'up'      => 'published',
                'css'     => 'danger',
                'color'   => 'red',
            ],

            [
                'value'   => 'published',
                'title'   => trans( 'ilib::status.published' ),
                'actions' => [
                    'read'          => true,
                    'update|delete' => function ( $model, $user ) {
                        /** @var \Minhbang\User\Support\HasOwner $model */
                        return $model && $user && (
                                Authority::user( $user )->isAdmin() || // Là Administrator
                                Authority::user( $user )->is( 'thu_vien.phu_trach' )  // Hoặc Phụ trách thư viện
                            );
                    },
                ],
                'down'    => 'pending',
                'css'     => 'success',
                'color'   => 'navy',
            ],
        ];
    }
}