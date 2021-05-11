<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected const LoginUserIdsSessionKey = 'login_user_ids';

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as _logout;
        credentials as _credentials;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::INDEX;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'loginUser');
        $this->middleware('auth')->only('loginUser');
    }

    protected function credentials(Request $request)
    {
        return array_merge([
            'status' => Status::Enabled
        ], $this->_credentials($request));
    }

    public function logout(Request $request)
    {
        $userIds = $request->session()->pull(static::LoginUserIdsSessionKey);
        if (empty($userIds)) {
            return $this->_logout($request);
        }

        $userId = array_pop($userIds);
        if (!empty($userIds)) {
            $request->session()->put(static::LoginUserIdsSessionKey, $userIds);
        }

        \Auth::loginUsingId($userId);
        return redirect('/');
    }

    public function loginUser(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $authUser = auth_user();
        if ($targetUser->employee) {
            abort_unless(
                $authUser->isRoot() || auth_employee()->authorize($targetUser->employee, 'login'),
                403
            );
        } else if ($targetUser->publisher) {
            abort_unless(
                $authUser->isRoot() || auth_employee()->authorize($targetUser->publisher, 'login'),
                403
            );
        } else {
            abort(403);
        }

        $request->session()->push(static::LoginUserIdsSessionKey, $authUser->id);
        \Auth::login($targetUser);

        return redirect('/');
    }
}
