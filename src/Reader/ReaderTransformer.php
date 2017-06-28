<?php namespace Minhbang\ILib\Reader;

use Minhbang\Kit\Extensions\ModelTransformer;
use Html;

/**
 * Class ReaderTransformer
 *
 * @package Minhbang\ILib
 */
class ReaderTransformer extends ModelTransformer {
    /**
     * Là Phụ trách thư viện?
     *
     * @var bool
     */
    protected $isPTTV = false;
    protected $allSecurity = [];

    public function __construct( $zone = 'backend' ) {
        parent::__construct( $zone );
        $this->isPTTV = user_is( 'thu_vien.phu_trach' );
        $this->allSecurity = Reader::allSecurity();
    }

    /**
     * @param \Minhbang\ILib\Reader\Reader $reader
     *
     * @return array
     */
    public function transform( Reader $reader ) {
        return [
            'id'          => (int) $reader->user_id,
            'code'        => Html::linkQuickUpdate(
                $reader->user_id,
                $reader->code,
                [
                    'label'     => $reader->code,
                    'attr'      => 'code',
                    'title'     => trans( 'ilib::reader.code' ),
                    'class'     => 'w-md',
                    'placement' => 'right',
                ],
                [ 'class' => 'a-code' ]
            ),
            'name'        => "{$reader->user_name} <small class=\"text-navy\"><em> - {$reader->user_username}</em></small>",
            'security_id' => $this->isPTTV ? Html::linkQuickUpdate(
                $reader->user_id,
                $reader->security_id,
                [
                    'label'     => $reader->security_title,
                    'attr'      => 'security_id',
                    'title'     => trans( "ilib::reader.security_id" ),
                    'class'     => 'w-md',
                    'placement' => 'left',
                ],
                [ 'class' => 'a-security_id' ]
            ) : $reader->security_title,
            'actions'     => Html::tableActions(
                $this->zone . '.reader',
                [ 'reader' => $reader->user_id ],
                $reader->user_name,
                trans( 'ilib::reader.reader' ),
                [
                    'renderEdit'   => false,
                    'renderDelete' => isset( $this->allSecurity[$reader->security_id] ) ? 'link' : 'disabled',
                ]
            ),
        ];
    }
}