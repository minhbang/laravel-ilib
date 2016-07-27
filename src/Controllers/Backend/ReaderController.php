<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\ILib\ReaderRequest;
use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\ILib\Reader;
use Datatable;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Request;
use Html;
use Minhbang\User\User;

/**
 * Class ReaderController
 * Quản lý bạn đọc
 * - Chọn User từ Hệ thống, cấp phép thành bạn đọc
 * - Gán quyền được đọc loại Tài liệu nào (không, mật,...)
 * - Gán quyền tạm thời được đọc 1 tài liệu nào đó
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class ReaderController extends BaseController
{
    use QuickUpdateActions;
    /**
     * @var string
     */
    protected $layout = 'ilib::layouts.backend';

    public $route_prefix = 'ilib.';

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
                ['sClass' => 'min-width', 'aTargets' => [-1, -2]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '',
                trans('ilib::reader.code_th'),
                trans('user::user.name'),
                trans('ilib::reader.security_id'),
                trans('common.actions')
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $name = trans('ilib::reader.reader');
        $this->buildHeading([trans('common.manage'), $name], 'fa-file-pdf-o', ['#' => $name]);

        // 10 users chưa phải là reader
        $selectize_users = User::forSelectize(Reader::all()->pluck('user_id'), 10)->get()->all();
        $reader = new Reader();

        return view(
            'ilib::backend.reader.index',
            compact('tableOptions', 'options', 'table', 'selectize_users') + $reader->loadEnums('id')
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
        $query = Reader::queryDefault()->withUser()->withEnumTitles()->orderUpdated();
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('readers.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('readers.updated_at', 'mb_date_vn2mysql');
        }

        return Datatable::query($query)
            ->addColumn(
                'index',
                function (Reader $model) {
                    return $model->user_id;
                }
            )
            ->addColumn(
                'code',
                function (Reader $model) {
                    return Html::linkQuickUpdate(
                        $model->user_id,
                        $model->code,
                        [
                            'label'     => $model->code,
                            'attr'      => 'code',
                            'title'     => trans('ilib::reader.code'),
                            'class'     => 'w-md',
                            'placement' => 'right',
                        ],
                        ['class' => 'a-code']
                    );
                }
            )
            ->addColumn(
                'name',
                function (Reader $model) {
                    return "{$model->user_name} <small class=\"text-navy\"><em> - {$model->user_username}</em></small>";
                }
            )
            ->addColumn(
                'security_id',
                function (Reader $model) {
                    return Html::linkQuickUpdate(
                        $model->user_id,
                        $model->security_id,
                        [
                            'label'     => $model->security,
                            'attr'      => 'security_id',
                            'title'     => trans("ilib::reader.security_id"),
                            'class'     => 'w-md',
                            'placement' => 'left',
                        ],
                        ['class' => 'a-security_id']
                    );
                }
            )
            ->addColumn(
                'actions',
                function (Reader $model) {
                    return Html::tableActions(
                        $this->route_prefix . 'backend.reader',
                        ['reader' => $model->user_id],
                        $model->user_name,
                        trans('ilib::reader.reader'),
                        [
                            'renderEdit' => false,
                        ]
                    );
                }
            )
            ->searchColumns('users.username', 'users.name')
            ->make();
    }

    /**
     * TODO: chưa xét unique user làm reader
     *
     * @param \Minhbang\ILib\ReaderRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ReaderRequest $request)
    {
        $reader = new Reader();
        $reader->fill($request->all());
        $reader->enumNotRestore = true;
        $reader->save();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans("ilib::reader.add_user_success"),
            ]
        );
    }

    /**
     * @param \Minhbang\ILib\Reader $reader
     *
     * @return \Illuminate\View\View
     */
    public function show(Reader $reader)
    {
        return view('ilib::backend.reader.show', compact('reader'));
    }

    /**
     * @param \Minhbang\ILib\Reader $reader
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Reader $reader)
    {
        $reader->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('ilib::reader.reader')]),
            ]
        );
    }

    /**
     * Lấy danh sách readers sử dụng cho selectize_user
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select($username)
    {
        return response()->json(
            Reader::forSelectize()->findText('users.username', $username)->get()->all()
        );
    }

    /**
     * Các attributes cho phép quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes()
    {
        return [
            'code'        => [
                'rules' => 'required|max:20',
                'label' => trans('ilib::reader.code'),
            ],
            'security_id' => [
                'rules' => 'required|max:255',
                'label' => trans('ilib::reader.security_id'),
            ],
        ];
    }

    /**
     * @param \Minhbang\ILib\Reader $model
     *
     * @return \Minhbang\ILib\Reader
     */
    protected function quickUpdateModel($model)
    {
        $model->enumNotRestore = true;

        return $model;
    }
}
