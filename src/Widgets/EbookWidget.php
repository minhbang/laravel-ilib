<?php
namespace Minhbang\ILib\Widgets;

use Minhbang\Ebook\Ebook;

/**
 * Class EbookWidget
 *
 * @package Minhbang\ILib\Widgets
 */
class EbookWidget
{
    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return string
     */
    public function itemTh(Ebook $ebook)
    {
        $url = route('ilib.ebook.show', ['ebook' => $ebook->id]);

        return <<<"ITEM"
<div class="col-md-4 col-sm-4 col-xs-6">
    <a href="$url" class="ebook-th-item">
        <div class="ebook-cover">
            {$ebook->present()->featured_image}
            <div class="details">
                <div class="inner">
                    {$ebook->writer}<br>
                    {$ebook->present()->fileicon} {$ebook->present()->filesize}
                    <i class="fa fa-eye"></i> {$ebook->hit}
                </div>
            </div>
            <div class="security">{$ebook->present()->securityFormated('primary')}</div>
        </div>
        <div class="title">{$ebook->title}</div>
    </a>
</div>
ITEM;
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return string
     */
    public function itemList(Ebook $ebook)
    {
        $ds = '';
        $ds .= '<dt>' . trans("ebook::common.language_id") . '</dt><dd>' . $ebook->language . '</dd>';
        $ds .= '<dt>' . trans("ebook::common.pages") . '</dt><dd>' . $ebook->pages . '</dd>';

        $url = route('ilib.ebook.show', ['ebook' => $ebook->id]);
        $publisher = trans("ebook::common.publisher_id_th") . ': ' . $ebook->publisher;

        return <<<"ITEM"
<div class="col-md-12">
    <div class="ebook-list-item">
        <a href="$url">
            <div class="ebook-cover">
                {$ebook->present()->featured_image}
                <div class="security">{$ebook->present()->securityFormated('primary')}</div>
            </div>
        </a>
        <div class="inner">
            <blockquote>
                <a href="$url"><div class="title">{$ebook->title}</div></a>
                <footer>{$ebook->writer}, {$publisher}, {$ebook->pyear}</footer>
            </blockquote>

            <div class="details">
                <dl class="dl-horizontal">$ds</dl>
                <small>{$ebook->present()->fileicon} {$ebook->present()->filesize}</small>
            </div>
        </div>
    </div>
</div>
ITEM;
    }

    /**
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @param string $type
     *
     * @return string
     */
    public function item(Ebook $ebook, $type = 'th')
    {
        $type = !in_array($type, ['th', 'list']) ? 'th' : $type;
        $method = 'item' . ucfirst($type);

        return $this->{$method}($ebook);
    }

    /**
     * @param \Minhbang\Ebook\Ebook[]|\Illuminate\Database\Eloquent\Collection $ebooks
     *
     * @param string $type
     *
     * @return string
     */
    public function items($ebooks, $type = 'th')
    {
        $html = '';
        foreach ($ebooks as $ebook) {
            $html .= $this->item($ebook, $type);
        }

        return $html ? "<div class=\"row\">{$html}</div>" : $this->emptyItems();
    }

    /**
     * @return string
     */
    public function emptyItems()
    {
        return '<div class="alert alert-danger">' . trans('ilib::common.empty_items') . '</div>';
    }

    /**
     * Render danh sách Ebooks theo định dạng bxSlider
     *
     * @see http://bxslider.com/
     *
     * @param \Minhbang\Ebook\Ebook[]|\Illuminate\Database\Eloquent\Collection $ebooks
     *
     * @return string
     */
    public function bxSlider($ebooks)
    {
        $html = '';
        foreach ($ebooks as $ebook) {
            $html .= '<div class="ebook-slider-item"></div>';
        }

        return $html ? "<div class=\"bxslider ebook-slider\">$html</div>" : '';
    }
}
