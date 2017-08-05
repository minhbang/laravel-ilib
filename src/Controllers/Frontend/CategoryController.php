<?php

namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Category\Category;
use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Widgets\EbookWidget;
use Minhbang\Option\OptionableController;
use Minhbang\ILib\DisplayOption;
use CategoryManager;

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
            'zone' => 'ilib',
            'group' => 'category',
            'class' => DisplayOption::class,
        ];
    }

    /**
     * Duyệt danh sách Tài liệu thuộc $category
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function show($slug)
    {
        abort_unless($slug && ($category = Category::findBySlug($slug)), 404, trans('category::common.not_fount'));
        CategoryManager::current($category);
        $paths = $category->getRoot1Path(['id', 'title'], false);
        $breadcrumbs = [];
        foreach ($paths as $cat) {
            $breadcrumbs[route('ilib.category.show', ['category' => $cat->id])] = $cat->title;
        }
        $breadcrumbs['#'] = $category->title;
        $this->buildHeading(
            $category->title,
            'fa-folder-open-o',
            $breadcrumbs
        );
        $ebooks = $this->optionAppliedPaginate(Ebook::queryDefault()->ready('read')->withEnumTitles()->categorized($category));
        $ebook_widget = new EbookWidget();

        return view('ilib::frontend.category.show', compact('category', 'ebooks', 'ebook_widget'));
    }
}
