<?php
use Minhbang\User\Seeders\UserTestData;
use DB;

/**
 * Class ILibManageTest
 */
class RouteTest extends TestCase
{
    use UserTestData;

    /**
     * Truy cập trang quản lý ilib
     */
    public function testGuestAccess()
    {
        // Yêu cầu đăng nhập khi truy cập
        $this->visit('/ilib/backend')
            ->seePageIs('/auth/login');
    }

    /**
     * Quản trị cấp cao
     */
    public function testSuperAdminAccess()
    {
        $this->actingAs($this->users['admin'])->visit('/ilib/backend')->see('Dashboard');
    }

    /**
     * Quản trị
     */
    public function testAdminAccess()
    {
        $this->actingAs($this->users['quantri'])->get('/ilib/backend')->seeStatusCode(403);
    }

    /**
     * Người dùng bình thường
     */
    public function testUserAccess()
    {
        $this->actingAs($this->users['user1'])->get('/ilib/backend')->seeStatusCode(403);
    }

    /**
     * Phụ trách thư viện
     */
    public function testPTAccess()
    {
        DB::table('role_user')->insert([
            'user_id'    => $this->users['user1']->id,
            'role_group' => 'tv',
            'role_name'  => 'pt',
        ]);
        $this->actingAs($this->users['user1'])->visit('/ilib/backend')->see('Dashboard');
    }

    /**
     * Nhân viên thư viện
     */
    public function testNVAccess()
    {
        DB::table('role_user')->insert([
            'user_id'    => $this->users['user2']->id,
            'role_group' => 'tv',
            'role_name'  => 'nv',
        ]);
        $this->actingAs($this->users['user2'])->visit('/ilib/backend')->see('Dashboard');
    }
}