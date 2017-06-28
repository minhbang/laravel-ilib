<?php namespace Minhbang\ILib\Reader;

use Minhbang\Kit\Extensions\ModelTransformer;
use Html;

/**
 * Class ReaderEbookTransformer
 *
 * @package Minhbang\ILib
 */
class ReaderEbookTransformer extends ModelTransformer {
    /**
     * Là Phụ trách thư viện?
     *
     * @var bool
     */
    protected $isPTTV = false;

    public function __construct( $zone = 'backend' ) {
        parent::__construct( $zone );
        $this->isPTTV = user_is( 'thu_vien.phu_trach' );
    }

    /**
     * @param \Minhbang\ILib\Reader\Reader $reader
     *
     * @return array
     */
    public function transform( Reader $reader ) {
        $expires_at = substr( mb_date_mysql2vn( $reader->expires_at, true, ' ' ), 0, - 3 );

        return [
            'id'         => (int) $reader->user_id,
            'code'       => $reader->code,
            'name'       => "{$reader->user_name} <small class=\"text-navy\"><em> - {$reader->user_username}</em></small>",
            'ebook'      => $reader->ebook_title,
            'expires_at' => $this->isPTTV ? Html::linkQuickUpdate(
                $reader->user_id,
                $expires_at,
                [
                    'attr'      => 'expires_at',
                    'title'     => trans( 'ilib::reader.expires_at' ),
                    'class'     => 'w-md no-focus',
                    'placement' => 'left',
                ],
                [ 'class' => 'a-expires_at' ],
                route(
                    $this->zone . '.reader_ebook.quick_update',
                    [ 'reader' => $reader->user_id, 'ebook' => $reader->ebook_id ]
                )
            ) : $expires_at,
            'actions'    => Html::tableActions(
                $this->zone . '.reader_ebook',
                [ 'reader' => $reader->user_id, 'ebook' => $reader->ebook_id ],
                trans(
                    'ilib::reader.allowed_title',
                    [ 'reader' => "{$reader->user_name} ({$reader->user_username})", 'ebook' => $reader->ebook_title ]
                ),
                trans( 'ilib::reader.allowed' ),
                [
                    'renderEdit'   => false,
                    'renderShow'   => false,
                    'renderDelete' => $this->isPTTV ? 'link' : 'disabled',
                ]
            ),
        ];
    }
}