<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\Ebook\Ebook;
use Minhbang\ILib\Reader;
use Minhbang\ILib\UploadRequest;
use Minhbang\ILib\Widgets\EbookWidget;
use Minhbang\Kit\Support\VnString;
//use Status;
use Minhbang\User\User;

/**
 * Class EbookController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class EbookController extends Controller
{
    /**
     * @var \Minhbang\Status\Traits\StatusManager;
     */
    protected $statusManager;

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
     * - Chỉ Đã đăng nhập mới được xem
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Ebook $ebook)
    {
        if ($ebook->isPublished()) {
            $ebook = $ebook->loadInfo();
            $cat_show = route('ilib.category.show', ['category' => $ebook->category->id]);
            $this->buildBreadcrumbs([
                $cat_show => $ebook->category->title,
                '#'       => $ebook->title,
            ]);

            $related_ebooks = $ebook->related(9)->get();
            $ebook_widget = new EbookWidget();

            return view('ilib::frontend.ebook.detail', compact('ebook', 'related_ebooks', 'ebook_widget'));
        } else {
            return view('message', [
                'module'  => trans('ilib::common.ilib'),
                'type'    => 'danger',
                'content' => trans('ilib::common.messages.ebook_unpublished'),
            ]);
        }
    }

    /**
     * Đọc toàn văn
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param string $slug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Ebook $ebook, $slug)
    {
        if ($this->checkPermission($ebook, $slug)) {
            $ebook = $ebook->loadInfo();
            $cat_show = route('ilib.category.show', ['category' => $ebook->category->id]);
            $this->buildBreadcrumbs([
                $cat_show => $ebook->category->title,
                '#'       => $ebook->title,
            ]);

            return view('ilib::frontend.ebook.view', compact('ebook'));
        } else {
            return view('message', [
                'module'  => trans('ilib::common.ilib'),
                'type'    => 'danger',
                'content' => trans('ilib::common.messages.unauthorized_full'),
            ]);
        }

    }

    /**
     * Download ebook file
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     * @param string $slug
     */
    public function download(Ebook $ebook, $slug)
    {
        if ($this->checkPermission($ebook, $slug)) {
            $ebook->updateRead();
            header("Content-type: {$ebook->filemime}");
            header('Content-Disposition: inline');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            readfile($ebook->filePath());
            exit();
        } else {
            return view('message', [
                'module'  => trans('ilib::common.ilib'),
                'type'    => 'danger',
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
        $ebook->fileFill($request);
        $ebook->user_id = user('id');
        $ebook->status = $this->statusManager->valueStatus('uploaded');
        $ebook->slug = VnString::to_slug($ebook->title);
        $ebook->enumNotRestore = true;
        $ebook->save();

        return view('message', [
            'module'  => trans('ilib::common.ilib'),
            'type'    => 'success',
            'content' => trans('ilib::common.messages.upload_success'),
            'buttons' => [
                [
                    route('ilib.index'),
                    trans('ilib::common.back_ilib_home'),
                    ['icon' => 'home', 'size' => 'sm', 'type' => 'success'],
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
        /** @var User $user */
        $user = user();
        /** @var Reader $reader */
        $reader = Reader::find($user->id);

        return $user->hasRole('tv.*') || ($ebook->isPublished() && $reader && $reader->canRead($ebook));
    }
}
