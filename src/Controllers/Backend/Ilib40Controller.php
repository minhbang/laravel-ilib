<?php
namespace Minhbang\ILib\Controllers\Backend;

use Minhbang\Kit\Extensions\BackendController;
use FTP;

/**
 * Class Ilib40Controller
 *
 * @package Minhbang\ILib\Controllers\Backend
 */
class Ilib40Controller extends BackendController
{
    /**
     * @var \Anchu\Ftp\Ftp
     */
    protected $ftp;
    /**
     * @var string
     */
    protected $ftp_root = '/Digital';

    public function __construct()
    {
        parent::__construct();
        $this->ftp = FTP::connection('tls_server1');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->buildHeading(
            ['Load Data', 'iLib 4.0'],
            'fa-download',
            [
                '#' => 'Load data from iLib 4.0',
            ]
        );
        $lists = $this->ftp->getDirListing($this->ftp_root);
        dd($lists);

        return view('ilib::backend.ilib40.index');
    }
}
