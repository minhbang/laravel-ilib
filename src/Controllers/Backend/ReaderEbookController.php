<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Ebook\Ebook;
use Minhbang\ILib\ReaderEbookRequest;
use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\ILib\Reader;
use Datatable;
use Request;
use Html;
use DB;

/**
 * Class ReaderEbookController
 * Phân quyền tạm thời reader được phép đọc 1 ebook nào đó
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class ReaderEbookController extends BaseController
{
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';

    protected $route_prefix = 'ilib.';

    /**
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tableOptions = [
            'id'        => 'reader-manage',
            'class'     => 'table-readers',
            'row_index' => true,
        ];
        $options = [
            'aoColumnDefs' => [
                ['sClass' => 'min-width text-right', 'aTargets' => [0, 1]],
                ['sClass' => 'min-width', 'aTargets' => [2, -1, -2]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '',
                trans('ilib::reader.code_th'),
                trans('user::user.name'),
                trans('ebook::common.ebook'),
                trans('ilib::reader.expires_at'),
                ''
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $this->buildHeading(
            [trans('common.manage'), trans('ilib::reader.allow_ebooks')],
            'fa-file-pdf-o',
            [
                route($this->route_prefix . 'backend.reader.index') => trans('ilib::reader.reader'),
                '#'                                                 => trans('ilib::reader.allowed'),
            ]
        );

        $readers = Reader::forSelectize()->get()->all();
        $ebooks = Ebook::forSelectize()->orderUpdated()->get()->all();

        return view(
            'ilib::backend.reader.ebook',
            compact('tableOptions', 'options', 'table', 'readers', 'ebooks')
        );
    }

    /**
     * Danh sách Reader theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var \Minhbang\ILib\Reader $query */
        $query = Reader::queryDefault()->allowedEbook()->withUser();
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('ebook_reader.expires_at', 'mb_date_vn2mysql');
        }

        return Datatable::query($query)
            ->addColumn(
                'index',
                function (Reader $model) {
                    return $model->id;
                }
            )
            ->addColumn(
                'code',
                function (Reader $model) {
                    return $model->code;
                }
            )
            ->addColumn(
                'name',
                function (Reader $model) {
                    return "{$model->user_name} <small class=\"text-navy\"><em> - {$model->user_username}</em></small>";
                }
            )
            ->addColumn(
                'ebook',
                function (Reader $model) {
                    return $model->ebook_title;
                }
            )
            ->addColumn(
                'expires',
                function (Reader $model) {
                    // chuyển ngày định dạng VN, bỏ phần giây :00 ở cuối
                    $expires_at = substr(mb_date_mysql2vn($model->expires_at), 0, -3);

                    return Html::linkQuickUpdate(
                        $model->id,
                        $expires_at,
                        [
                            'attr'      => 'expires_at',
                            'title'     => trans('ilib::reader.expires_at'),
                            'class'     => 'w-md no-focus',
                            'placement' => 'left',
                        ],
                        ['class' => 'a-expires_at'],
                        route(
                            $this->route_prefix . 'backend.reader_ebook.quick_update',
                            ['reader' => $model->id, 'ebook' => $model->ebook_id]
                        )
                    );
                }
            )
            ->addColumn(
                'actions',
                function (Reader $model) {
                    return Html::tableActions(
                        $this->route_prefix . 'backend.reader_ebook',
                        ['reader' => $model->id, 'ebook' => $model->ebook_id],
                        trans(
                            'ilib::reader.allowed_title',
                            ['reader' => "{$model->user_name} ({$model->user_username})", 'ebook' => $model->ebook_title]
                        ),
                        trans('ilib::reader.allowed'),
                        [
                            'renderEdit' => false,
                            'renderShow' => false,
                        ]
                    );
                }
            )
            ->searchColumns('users.username', 'users.name')
            ->make();
    }

    /**
     * @param \Minhbang\ILib\ReaderEbookRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ReaderEbookRequest $request)
    {
        $inputs = $request->all();
        /** @var \Minhbang\ILib\Reader $reader */
        $reader = Reader::findOrFail($inputs['reader_id']);
        $reader->ebooks()->attach($inputs['ebook_id'], ['expires_at' => $this->buildExpiresAt($inputs['expires_at'])]);

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans("ilib::reader.add_allow_ebooks_success"),
            ]
        );
    }

    /**
     * @param \Minhbang\ILib\Reader $reader
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Reader $reader, Ebook $ebook)
    {
        $reader->ebooks()->detach($ebook->id);

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('ilib::reader.allowed')]),
            ]
        );
    }

    /**
     * @param \Minhbang\ILib\Reader $reader
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate(Reader $reader, Ebook $ebook)
    {
        $attr = Request::get('_attr');
        if (in_array($attr, ['expires_at'])) {
            $value = Request::get('_value');
            switch ($attr) {
                case 'expires_at':
                    DB::table('ebook_reader')->where('reader_id', $reader->id)->where('ebook_id', $ebook->id)
                        ->update([
                            'expires_at' => $this->buildExpiresAt($value),
                            'updated_at' => date("Y-m-d H:m:s"),
                        ]);
                    break;
                default:
                    break;
            }

            return response()->json(
                [
                    'type'    => 'success',
                    'message' => trans('common.quick_update_success', ['attribute' => trans("ilib::reader.{$attr}")]),
                ]
            );
        } else {
            return response()->json(['type' => 'error', 'message' => 'Invalid quick update request!']);
        }
    }

    /**
     * @param string $datetime_vn
     *
     * @return string
     */
    protected function buildExpiresAt($datetime_vn)
    {
        // chuyển ngày giờ định dạng MySQL, thêm phần giây :00 ở cuối
        return mb_date_vn2mysql($datetime_vn) . ':00';
    }
}
