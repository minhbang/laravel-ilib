<?php
namespace Minhbang\ILib;

use Minhbang\Status\Managers\StatusManager;
use UserManager;

/**
 * Class EbookStatus
 *
 * @package Minhbang\ILib
 */
class EbookStatus extends StatusManager
{
    /**
     * Mới tải lên: ebook do bạn đọc upload
     */
    const STATUS_UPLOADED = 1;
    /**
     * Đang biên mục: chỉ nhân viên thư viện được phép xem, Mặc định
     */
    const STATUS_PROCESSING = 2;
    /**
     * Chờ duyệt: chỉ nhân viên và phụ trách thư viện được xem
     */
    const STATUS_PENDING = 3;
    /**
     * Đã xuất bản: được phép xem
     */
    const STATUS_PUBLISHED = 4;

    /**
     * Định nghĩa tât cả statuses
     *
     * @return array
     */
    protected function allStatuses()
    {
        // user() là Phụ trách Thư viện
        $isPT = UserManager::user()->hasRole('tv.pt');

        return [
            [
                'name'   => 'uploaded',
                'value'  => static::STATUS_UPLOADED,
                'title'  => trans('ilib::common.status_uploaded'),
                'action' => trans('ilib::common.status_action_uploaded'),
                'css'    => 'warning',
                'rule'   => [static::STATUS_PROCESSING],
            ],
            [
                'name'   => 'processing',
                'value'  => static::STATUS_PROCESSING,
                'title'  => trans('ilib::common.status_processing'),
                'action' => trans('ilib::common.status_action_processing'),
                'css'    => 'danger',
                'rule'   => $isPT ? [static::STATUS_PUBLISHED] : [static::STATUS_PENDING],
            ],
            [
                'name'   => 'pending',
                'value'  => static::STATUS_PENDING,
                'title'  => trans('ilib::common.status_pending'),
                'action' => trans('ilib::common.status_action_pending'),
                'css'    => 'info',
                'rule'   => $isPT ? [static::STATUS_PROCESSING, static::STATUS_PUBLISHED] : [static::STATUS_PROCESSING],
            ],
            [
                'name'   => 'published',
                'value'  => static::STATUS_PUBLISHED,
                'title'  => trans('ilib::common.status_published'),
                'action' => trans('ilib::common.status_action_published'),
                'css'    => 'primary',
                'rule'   => $isPT ? [static::STATUS_PROCESSING] : [],
            ],
        ];
    }

    /**
     * @return int
     */
    public function valueDefault()
    {
        return static::STATUS_PROCESSING;
    }

    /**
     * @return array
     */
    public function valuesPublished()
    {
        return [static::STATUS_PUBLISHED];
    }

    /**
     * User hiện tại có thể DELETE ebook có trạng thái $status không?
     *
     * @param int|string $status
     *
     * @return bool
     */
    public function canDelete($status)
    {
        return UserManager::user()->hasRole('tv.pt') || !$this->checkPublished($status);
    }

    /**
     * User hiện tại có thể UPDATE ebook có trạng thái $status không?
     *
     * @param int|string $status
     *
     * @return bool
     */
    public function canUpdate($status)
    {
        return $this->canDelete($status);
    }

    /**
     * User hiện tại có thể READ ebook có trạng thái $status không?
     *
     * @param $status
     *
     * @return bool
     */
    public function canRead($status)
    {
        return UserManager::user()->hasRole('tv.*') || $this->checkPublished($status);
    }
}