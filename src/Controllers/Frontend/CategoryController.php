<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Category\Category as Category;
use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Widgets\EbookWidget;
use Minhbang\Option\OptionableController;
use Minhbang\ILib\DisplayOption;

/**
 * Class CategoryController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class CategoryController extends Controller
{
    use OptionableController;

    /**
     * @return array
     */
    protected function optionConfig()
    {
        return [
            'zone'  => 'ilib',
            'group' => 'category',
            'class' => DisplayOption::class,
        ];
    }

    /**
     * Duyệt danh sách Tài liệu thuộc $category
     *
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Category $category)
    {
        $paths = $category->getRoot1Path(['id', 'title'], false);
        $breadcrumbs = [];
        foreach ($paths as $cat) {
            $breadcrumbs[route('ilib.category.show', ['category' => $cat->id])] = $cat->title;
        }
        $breadcrumbs['#'] = $category->title;
        $this->buildBreadcrumbs($breadcrumbs);
        $ebooks = $this->optionAppliedPaginate(Ebook::queryDefault()->published()->withEnumTitles()->categorized($category));
        $ebook_widget = new EbookWidget();

        return view('ilib::frontend.category.show', compact('category', 'ebooks', 'ebook_widget'));
    }
}
