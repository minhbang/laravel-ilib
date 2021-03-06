<?php

namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Kit\Extensions\BackendController as BaseController;

/**
 * Todo: chưa hiện thực
 *
 * Class UserController
 * Quản lý nhân viên thư viện
 * - Chỉ phụ trách thư viện (hay admin) mới có quyền truy cập
 * - Chọn User từ Hệ thống, gán cho các roles của role_group 'Thư viện'
 * - Thống kê công việc nhân viên đã làm (biên mục tài liệu...)
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class UserController extends BaseController {
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';
}
