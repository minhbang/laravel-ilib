<?php namespace Minhbang\ILib;

use Minhbang\Kit\Extensions\ModelTransformer;

/**
 * Class ReadEbookTransformer
 *
 * @package Minhbang\ILib
 */
class ReadEbookTransformer extends ModelTransformer {
    protected static $count = 0;

    /**
     * @param $model
     *
     * @return array
     */
    public function transform( $model ) {
        return [
            'id'      => self::$count ++,
            'name'    => $model->name,
            'title'   => '<a href="' . route( 'ilib.backend.ebook.show', [ 'ebook' => $model->ebook_id ] ) . '">' . $model->title . '</a>',
            'read_at' => mb_date_mysql2vn( $model->read_at ),
        ];
    }
}