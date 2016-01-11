<?php
namespace Minhbang\ILib;

use Minhbang\LaravelKit\Extensions\Request as BaseRequest;

/**
 * Class Request
 *
 * @package Minhbang\Ebook
 */
class ReaderEbookRequest extends BaseRequest
{
    public $trans_prefix = 'ilib::reader';
    public $rules = [
        'expires_at' => 'required',
        'reader_id'   => 'required|integer|min:1',
        'ebook_id'    => 'required|integer|min:1',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

}
