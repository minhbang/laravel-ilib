<?php

namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Ebook\Ebook;
use Minhbang\File\File;
use Minhbang\ILib\Reader\Reader;
use Minhbang\ILib\UploadRequest;
use Minhbang\ILib\Widgets\EbookWidget;
use Minhbang\Kit\Support\VnString;

//use Status;

/**
 * Class EbookController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class EbookController extends Controller
{
    /**
     * EbookController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //$this->statusManager = Status::of(Ebook::class);
    }

    /**
     * Xem chi tiết
     * - Chỉ Bạn đọc Đã đăng nhập mới được xem
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Ebook $ebook)
    {
        if ($ebook->isReady('read')) {
            $ebook = $ebook->loadInfo();
            $cat_show = route('ilib.category.show', ['slug' => $ebook->category->slug]);
            $this->buildHeading($ebook->title, 'fa-book', [
                $cat_show => $ebook->category->title,
                '#' => $ebook->title,
            ]);
            $related_ebooks = $ebook->related(9)->get();
            $ebook_widget = new EbookWidget();

            return view('ilib::frontend.ebook.detail', compact('ebook', 'related_ebooks', 'ebook_widget'));
        } else {
            return view('message', [
                'module' => trans('ilib::common.ilib'),
                'type' => 'danger',
                'content' => trans('ilib::common.messages.ebook_unpublished'),
            ]);
        }
    }

    /**
     * Đọc toàn văn
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param \Minhbang\File\File $file
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Ebook $ebook, File $file, $slug)
    {
        if ($this->checkPermission($ebook, $slug)) {
            $ebook = $ebook->loadInfo();
            $heading = $ebook->files->count() > 1 ? "{$ebook->title}: <span>{$file->present()->icon} {$file->title}</span>" : "{$file->present()->icon} $ebook->title";
            $cat_show = route('ilib.category.show', ['slug' => $ebook->category->slug]);
            $this->buildHeading($heading, null, [
                $cat_show => $ebook->category->title,
                '#' => $ebook->title,
            ]);
            $url = route('ilib.ebook.download', ['file' => $file->id, 'ebook' => $ebook->id, 'slug' => $ebook->slug]);
            $locale = config('app.locale');

            return view('ilib::frontend.ebook.view', compact('locale', 'url'));
        } else {
            return view('message', [
                'module' => trans('ilib::common.ilib'),
                'type' => 'danger',
                'content' => trans('ilib::common.messages.unauthorized_full'),
            ]);
        }
    }

    /**
     * Download ebook file
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param \Minhbang\File\File $file
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function download(Ebook $ebook, File $file, $slug)
    {
        if ($this->checkPermission($ebook, $slug)) {
            $ebook->updateRead();
            $file->response();
        } else {
            return view('message', [
                'module' => trans('ilib::common.ilib'),
                'type' => 'danger',
                'content' => trans('ilib::common.messages.unauthorized_full'),
            ]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        $ebook = new Ebook();

        return view('ilib::frontend.ebook.upload', compact('ebook'));
    }

    /**
     * @param \Minhbang\ILib\UploadRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(UploadRequest $request)
    {
        $ebook = new Ebook();
        $ebook->fill($request->only(['title', 'summary']));
        $ebook->user_id = user('id');
        $ebook->status = $ebook->statusManager()->defaultStatus();
        $ebook->slug = VnString::to_slug($ebook->title);
        $ebook->save();

        $file = new File();
        $error = $file->fillRequest($request);
        if ($error) {
            $ebook->delete();
        } else {
            $ebook->fillFiles($file->id);
        }
        $msg_type = $error ? 'danger' : 'success';

        return view('message', [
            'module' => trans('ilib::common.ilib'),
            'type' => $msg_type,
            'content' => trans("ilib::common.messages.upload_{$msg_type}").($error ? "<p class='error'>\"$error\"</p>" : ''),
            'buttons' => [
                [route('ilib.index'), trans('ilib::common.back_ilib_home'), ['icon' => 'fa-home', 'type' => 'primary']],
                [
                    route('ilib.ebook.upload'),
                    trans($error ? 'common.retry' : 'ilib::common.upload_more'),
                    ['icon' => 'fa-upload', 'type' => 'success'],
                ],
            ],
        ]);
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param string $slug
     *
     * @return bool
     */
    protected function checkPermission($ebook, $slug)
    {
        abort_unless($slug == $ebook->slug, 404);
        $reader = Reader::current();

        return authority()->user()->hasRole('thu_vien.*') || ($ebook->isReady('read') && $reader && $reader->canRead($ebook));
    }
}
