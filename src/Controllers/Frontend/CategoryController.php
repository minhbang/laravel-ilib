<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Category\Item as Category;
use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Widgets\EbookWidget;

/**
 * Class CategoryController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class CategoryController extends Controller
{
    /**
     * @var string
     */
    protected $options_group = 'category';
    /**
     * @var string
     */
    protected $options_model = 'Minhbang\ILib\Options\DisplayOption';

    /**
     * @param \Minhbang\Category\Item $category
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
        $ebooks = $this->getPaginate(Ebook::queryDefault()->withEnumTitles()->categorized($category));
        $ebook_widget = new EbookWidget();

        return view('ilib::frontend.category.show', compact('category', 'ebooks', 'ebook_widget'));
    }
}
