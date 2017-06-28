<?php

namespace Minhbang\ILib\Reader;

use Minhbang\Kit\Traits\Presenter\DatetimePresenter;
use Laracasts\Presenter\Presenter;

/**
 * Class Presenter
 *
 * @property \Minhbang\ILib\Reader\Reader $entity
 * @package Minhbang\Ebook
 */
class ReaderPresenter extends Presenter {
    use DatetimePresenter;

    public function security() {
        /** @var \Minhbang\Enum\EnumModel $security */
        $security = $this->entity->getEnumValue( 'security_id', false );

        return $security ? "<span class=\"label label-{$security->params}\">{$security->title}</span>" : null;
    }
}