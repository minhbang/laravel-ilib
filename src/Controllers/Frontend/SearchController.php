<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Widgets\EbookWidget;
use Illuminate\Http\Request;
use Minhbang\Category\Category as Category;
use Minhbang\Option\OptionableController;
use Minhbang\ILib\DisplayOption;
/**
 * Class SearchController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class SearchController extends Controller
{
    use OptionableController;

    /**
     * @return array
     */
    protected function optionConfig()
    {
        return [
            'zone'  => 'ilib',
            'group' => 'search',
            'class' => DisplayOption::class,
        ];
    }

    /**
     * Chuyển đổi search params 'key' thành 'column name',
     * ===> Gọn + dấu 'column name' thật trên url
     *
     * @var array
     */
    protected $key_column = [
        'pys' => 'pyear_start',
        'pye' => 'pyear_end',

        'ct' => 'category_id',
        'lg' => 'language_id',
        'sc' => 'security_id',
        'wt' => 'writer_id',
        'pl' => 'publisher_id',
        'pp' => 'pplace_id',
    ];

    /**
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->buildBreadcrumbs([
            '#' => trans('ilib::common.search'),
        ]);

        $q = $request->get('q');
        $params = $request->only(array_keys($this->key_column));
        $attributes = $this->getAttributes($params);
        $advanced = !empty($attributes);
        $category_id = mb_array_extract('category_id', $attributes);

        $pyear_start = (int)mb_array_extract('pyear_start', $attributes);
        $pyear_end = (int)mb_array_extract('pyear_end', $attributes);
        $pyear_end = $pyear_end >= $pyear_start ? $pyear_end : 0;

        $query = Ebook::queryDefault()->published()->withEnumTitles()->withCategoryTitle()
            ->whereAttributes($attributes)->searchKeyword($q);

        if ($pyear_start || $pyear_end) {
            if ($pyear_start) {
                if ($pyear_end) {
                    $query->whereBetween('ebooks.pyear', [$pyear_start, $pyear_end]);
                } else {
                    $query->where('ebooks.pyear', '>=', $pyear_start);
                }
            } else {
                $query->where('ebooks.pyear', '<=', $pyear_end);
            }
        }

        if ($category_id && ($category = Category::find($category_id))) {
            $query->categorized($category);
        }

        $ebooks = $this->optionAppliedPaginate($query);
        $total = $ebooks->total();
        $ebook_widget = new EbookWidget();
        $categories = app('category-manager')->root('ebook')->selectize();
        $enums = (new Ebook())->loadEnums('id');

        $column_key = array_combine(
            array_values($this->key_column),
            array_keys($this->key_column)
        );

        return view(
            'ilib::frontend.search.index',
            $enums + compact('q', 'ebooks', 'ebook_widget', 'total', 'categories', 'params', 'column_key', 'advanced')
        );
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function getAttributes($params)
    {
        $attributes = [];
        foreach ($params as $key => $value) {
            if ($value && isset($this->key_column[$key])) {
                $attributes[$this->key_column[$key]] = $value;
            }
        }

        return $attributes;
    }
}
