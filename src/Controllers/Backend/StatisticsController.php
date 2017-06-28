<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Category\Category;
use Minhbang\Kit\Extensions\BackendController;
use DB;
use CategoryManager;
use Minhbang\Ebook\Ebook;
use Datatables;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Minhbang\ILib\ReadEbookTransformer;

/**
 * Class StatisticsController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class StatisticsController extends BackendController {
    protected $layout = 'ilib::layouts.backend';
    public $route_prefix = 'ilib.';

    /**
     * Thống kê Tài liệu số theo các thuộc tính Enum
     *
     * @param string $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enum( $type ) {
        abort_unless( in_array( $type, [ 'language', 'security', 'writer', 'publisher', 'pplace' ] ), 404 );

        $this->buildHeading(
            [ trans( "ilib::common.statistics" ), trans( "ilib::common.statistics_{$type}" ) ],
            'fa-bar-chart',
            [ '#' => trans( "ilib::common.statistics" ) ]
        );
        $couters = DB::table( 'ebooks' )->leftJoin( 'enums', "ebooks.{$type}_id", '=', 'enums.id' )->groupBy( 'enums.title' )
                     ->select( DB::raw( 'COUNT(*) AS enum_count' ), 'enums.title as title' )->orderBy( 'enum_count', 'desc' )->get();
        $type_title = trans( "ebook::common.{$type}_id" );

        return view( 'ilib::backend.statistics.enum', compact( 'type_title', 'couters' ) );
    }

    /**
     * Thống kê Tài liệu số theo Danh mục
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category() {
        $this->buildHeading(
            [ trans( "ilib::common.statistics" ), trans( "ilib::common.statistics_category" ) ],
            'fa-bar-chart',
            [ '#' => trans( "ilib::common.statistics" ) ]
        );
        /** @var Category[] $categories */
        $categories = CategoryManager::of( Ebook::class )->node()->getDescendants();
        $data = [];
        foreach ( $categories as $category ) {
            $data[] = [
                'title' => $category->title,
                'depth' => $category->depth,
                'count' => Ebook::categorized( $category )->count(),
            ];
        }

        return view( 'ilib::backend.statistics.category', compact( 'data' ) );
    }

    /**
     * Thống kê đọc lượt tài liệu
     *
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function read( Builder $builder ) {
        $this->buildHeading(
            [ trans( "ilib::common.statistics" ), trans( "ilib::common.statistics_read" ) ],
            'fa-bar-chart',
            [ '#' => trans( "ilib::common.statistics" ) ]
        );
        $builder->ajax( route( $this->route_prefix . 'backend.statistics.read_data' ) );
        $html = $builder->columns( [
            [ 'data' => 'id', 'name' => 'id', 'title' => '#', 'class' => 'min-width text-right', 'orderable' => false, 'searchable' => false, ],
            [ 'data' => 'name', 'name' => 'users.name', 'title' => trans( 'ilib::reader.reader' ), 'class' => 'min-width' ],
            [ 'data' => 'title', 'name' => 'ebooks.title', 'title' => trans( 'ebook::common.ebook' ) ],
            [ 'data' => 'read_at', 'name' => 'read_ebook.read_at', 'title' => trans( 'ilib::reader.read_at' ), 'class' => 'min-width' ],
        ] );

        return view( 'ilib::backend.statistics.read', compact( 'html' ) );
    }

    /**
     * @return mixed
     */
    public function read_data() {
        $query = DB::table( 'read_ebook' )
                   ->leftJoin( 'users', 'read_ebook.reader_id', '=', 'users.id' )
                   ->leftJoin( 'ebooks', 'read_ebook.ebook_id', '=', 'ebooks.id' )
                   ->select( 'users.name', 'ebooks.title', 'read_ebook.read_at', 'read_ebook.ebook_id' );

        return Datatables::of( $query )->setTransformer( new ReadEbookTransformer( $this->route_prefix . 'backend' ) )->make( true );
    }

}
