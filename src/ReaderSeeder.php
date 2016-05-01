<?php
namespace Minhbang\ILib;

use Minhbang\User\User;
use Minhbang\Enum\Enum;
use DB;

/**
 * Class ReaderSeeder
 *
 * @package Minhbang\ILib
 */
class ReaderSeeder
{
    /**
     * @param array $data
     */
    public function seed($data)
    {
        DB::table('readers')->truncate();
        
        $readers = [];
        foreach ($data as $username => $security) {
            /** @var User $user */
            $user = User::findBy('username', $username);
            /** @var Enum $enum */
            $enum = Enum::where('slug', $security)->where('type', 'ebook.security')->first();
            if ($user && $enum) {
                $readers[] = [
                    'user_id'     => $user->id,
                    'security_id' => $enum->id,
                    'code'        => "RD-{$user->id}",
                ];
            }
        }
        if ($readers) {
            DB::table('readers')->insert($readers);
        }
    }
}