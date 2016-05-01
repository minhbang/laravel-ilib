<?php
namespace Minhbang\ILib;

use Minhbang\Kit\Extensions\Request as BaseRequest;

/**
 * Class ReaderRequest
 *
 * @package Minhbang\ILib
 */
class ReaderRequest extends BaseRequest
{
    public $trans_prefix = 'ilib::reader';
    public $rules = [
        'code'        => 'required|max:20',
        'user_id'     => 'required|integer|min:1',
        'security_id' => 'required|integer|min:1',
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
