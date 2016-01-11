<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\LaravelKit\Extensions\Controller as BaseController;
use View;
use Category;

/**
 * Class Controller
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
abstract class Controller extends BaseController
{
    /**
     * @var string
     */
    protected $options_group;
    /**
     * @var
     */
    protected $options_model;

    /**
     * @var \Minhbang\ILib\Options\Option
     */
    public $options;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        View::share('ebook_category', Category::manage('ebook'));
        $this->loadOptions();
    }

    /**
     * @param array|string $breadcrumbs
     * @param bool $homeItem
     *
     * @return array
     */
    protected function buildBreadcrumbs($breadcrumbs, $homeItem = true)
    {
        $breadcrumbs = [route('ilib.index') => trans('ilib::common.ilib')] + $breadcrumbs;

        return parent::buildBreadcrumbs($breadcrumbs, $homeItem);
    }


    /**
     * Load các thiết lập
     */
    protected function loadOptions()
    {
        if ($this->options_group && $this->options_model && is_null($this->options)) {
            $this->options = new $this->options_model($this->options_group);
            View::share("{$this->options_group}_options", $this->options);
        }
    }

    /**
     * @param  \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model $query
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getPaginate($query)
    {
        list($column, $direction) = $this->options->column('sort');
        if ($column) {
            $query->orderBy($column, $direction);
        }

        return $query->paginate($this->options->get('page_size', 6));
    }
}
