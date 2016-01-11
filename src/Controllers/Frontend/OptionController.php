<?php
namespace Minhbang\ILib\Controllers\Frontend;

use Minhbang\ILib\Option;

/**
 * Class OptionController
 *
 * @package Minhbang\ILib\Controllers\Frontend
 */
class OptionController extends Controller
{
    /**
     * @param string $group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function set($group)
    {
        $options = new Option($group);

        return response()->json($options->get());
    }
}
