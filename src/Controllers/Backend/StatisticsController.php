<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Category\Category;
use Minhbang\Kit\Extensions\BackendController;
use DB;
use CategoryManager;
use Minhbang\Ebook\Ebook;
use Datatable;

/**
 * Class StatisticsController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class StatisticsController extends BackendController
{
    protected $layout = 'ilib::layouts.backend';

    /**
     * @param string $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enum($type)
    {
        abort_unless(in_array($type, ['language', 'security', 'writer', 'publisher', 'pplace']), 404);

        $this->buildHeading(
            [trans("ilib::common.statistics"), trans("ilib::common.statistics_{$type}")],
            'fa-bar-chart',
            ['#' => trans("ilib::common.statistics")]
        );
        $couters = DB::table('ebooks')->leftJoin('enums', "ebooks.{$type}_id", '=', 'enums.id')->groupBy('enums.title')
            ->select(DB::raw('COUNT(*) AS enum_count'), 'enums.title as title')->orderBy('enum_count', 'desc')->get();
        $type_title = trans("ebook::common.{$type}_id");

        return view('ilib::backend.statistics.enum', compact('type_title', 'couters'));
    }

    public function category()
    {
        $this->buildHeading(
            [trans("ilib::common.statistics"), trans("ilib::common.statistics_category")],
            'fa-bar-chart',
            ['#' => trans("ilib::common.statistics")]
        );
        /** @var Category[] $categories */
        $categories = CategoryManager::of(Ebook::class)->root()->getDescendants();
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'title' => $category->title,
                'depth' => $category->depth,
                'count' => Ebook::categorized($category)->count(),
            ];
        }

        return view('ilib::backend.statistics.category', compact('data'));
    }

    public function read()
    {
        $tableOptions = [
            'id'        => 'read-manage',
            'row_index' => true,
        ];
        $options = [
            'aoColumnDefs' => [
                ['sClass' => 'min-width text-right', 'aTargets' => [0]],
                ['sClass' => 'min-width', 'aTargets' => [1, -1]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '',
                trans('ilib::reader.reader'),
                trans('ebook::common.ebook'),
                trans('ilib::reader.read_at')
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $this->buildHeading(
            [trans("ilib::common.statistics"), trans("ilib::common.statistics_read")],
            'fa-bar-chart',
            ['#' => trans("ilib::common.statistics")]
        );

        return view('ilib::backend.statistics.read', compact('tableOptions', 'options', 'table'));
    }

    public function read_data()
    {
        $query = DB::table('read_ebook')
            ->leftJoin('users', 'read_ebook.reader_id', '=', 'users.id')
            ->leftJoin('ebooks', 'read_ebook.ebook_id', '=', 'ebooks.id')
            ->select('users.name', 'ebooks.title', 'read_ebook.read_at', 'read_ebook.ebook_id');

        return Datatable::query($query)
            ->addColumn(
                'index',
                function () {
                    return '#';
                }
            )
            ->addColumn(
                'name',
                function ($model) {
                    return $model->name;
                }
            )
            ->addColumn(
                'title',
                function ($model) {
                    return '<a href="'.route('ilib.backend.ebook.show', ['ebook' => $model->ebook_id]).'">'.$model->title.'</a>';
                }
            )
            ->addColumn(
                'read_at',
                function ($model) {
                    return mb_date_mysql2vn($model->read_at);
                }
            )
            ->searchColumns('users.name', 'ebooks.title', 'read_ebook.read_at')
            ->make();
    }

}
