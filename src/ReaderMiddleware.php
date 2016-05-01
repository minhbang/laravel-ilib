<?php
namespace Minhbang\ILib;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Minhbang\User\User;
use Session;

class ReaderMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $action
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $action = 'action')
    {
        if ($this->auth->guest()) {
            // Chưa đăng nhập
            if ($request->ajax()) {
                return response('iLib unauthorized access.', 401);
            } else {
                Session::flash('title', trans('ilib::common.login'));
                Session::flash('message', [
                    'type'    => 'warning',
                    'content' => trans('ilib::common.messages.login_to', ['action' => trans("ilib::common.{$action}")]),
                ]);

                return redirect()->guest(route('auth.login'));
            }
        } else {
            // kiểm tra user có tài khoản Reader
            /** @var User $user */
            $user = user();
            if ($user->isSysSadmin() || Reader::find($user->id) || $user->hasRole('tv.*')) {
                return $next($request);
            } else {
                return response()->view('message', [
                    'module'  => trans('ilib::common.ilib'),
                    'type'    => 'danger',
                    'content' => trans('ilib::common.messages.not_reader', ['action' => trans("ilib::common.{$action}")]),
                ]);
            }
        }
    }
}