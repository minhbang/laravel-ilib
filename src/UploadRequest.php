<?php

namespace Minhbang\ILib;

use Minhbang\Kit\Extensions\Request as BaseRequest;

/**
 * Class UploadRequest
 *
 * @package Minhbang\ILib
 */
class UploadRequest extends BaseRequest {
    public $trans_prefix = 'ebook::common';
    public $rules = [
        'title'   => 'required|max:255',
        'summary' => 'required',
        'name'    => 'required|mimes:pdf',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->rules['name'] .= '|max:' . config( 'ebook.max_file_size' );

        return $this->rules;
    }

}
