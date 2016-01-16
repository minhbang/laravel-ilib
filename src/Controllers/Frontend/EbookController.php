<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Widgets\EbookWidget;

/**
 * Class EbookController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class EbookController extends Controller
{
    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Ebook $ebook)
    {
        $ebook->timestamps = false;
        $ebook->hit = $ebook->hit + 1;
        $ebook->save();

        $ebook = $ebook->loadInfo();
        $cat_show = route('ilib.category.show', ['category' => $ebook->category->id]);
        $this->buildBreadcrumbs([
            $cat_show => $ebook->category->title,
            '#'       => $ebook->title,
        ]);

        $related_ebooks = $ebook->related(9)->get();
        $ebook_widget = new EbookWidget();

        return view('ilib::frontend.ebook.show', compact('ebook', 'related_ebooks', 'ebook_widget'));
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param string $slug
     */
    public function full(Ebook $ebook, $slug)
    {
        if ($slug !== $ebook->slug) {
            abort(404);
        }
        //Todo: kiểm tra quyền truy cập của User
        header("Content-type: {$ebook->filemime}");
        header('Content-Disposition: inline');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        readfile($ebook->filePath());
    }
}
