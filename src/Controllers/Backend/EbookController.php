<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\BackendController as BaseController;
use Minhbang\Ebook\Ebook;

/**
 * Class EbookController
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class EbookController extends BaseController
{
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';
    /**
     * @var string
     */
    public $route_prefix = 'ilib.';

    public $allStatus = false;

    /**
     * Lấy danh sách ebooks sử dụng cho selectize ebooks
     *
     * @param string $title
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select($title)
    {
        return response()->json(
            Ebook::forSelectize()->orderUpdated()->findText('title', $title)->get()->all()
        );
    }
}