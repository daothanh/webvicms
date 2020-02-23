<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Two\User as SocialUser;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Entities\ConnectedAccount;
use Modules\User\Entities\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Rules\Activated;
use Socialite;

class LoginController extends Controller
{
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

    use AuthenticatesUsers;

    protected $user;

    protected $username = 'email';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->middleware('guest')->except('logout');
        $this->user = $user;
    }

    /**
     * Show the application's login form.
     *
     */
    public function showLoginForm()
    {
        $this->seo()->setTitle(__('Login'));
        return $this->view('user::auth.login');
    }

    public function showAdminLoginForm()
    {
        $this->seo()->setTitle(__('Login'));
        return $this->view('user::auth.admin_login');
    }

    public function adminLogin(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => [
                'required',
                'string',
                'exists:users,'.$this->username(),
                new Activated(),
            ],
            'password' => 'required|string',
        ]);
    }

    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
        /** @var SocialUser $gUser */
        $gUser = Socialite::driver('google')->user();
        $user = $this->getUserBySocial('google', $gUser);
        if (!$user) {
            $request->session()->put('social_user', $gUser);
            $request->session()->put('social_provider', 'google');
            return redirect()->route('register');
        }
        $this->guard()->login($user);
        return $this->authenticated($request, $user);
    }

    public function facebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(Request $request)
    {
        /** @var SocialUser $fbUser */
        $fbUser = Socialite::driver('facebook')->user();
        $user = $this->getUserBySocial('facebook', $fbUser);
        if (!$user) {
            $request->session()->put('social_user', $fbUser);
            $request->session()->put('social_provider', 'facebook');
            return redirect()->route('register');
        }
        $this->guard()->login($user);
        return $this->authenticated($request, $user);
    }

    public function username()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     *
     * Lấy thông tin user từ social account
     * @param $socialName
     * @param $socialUser SocialUser
     * @return User
     */
    protected function getUserBySocial($socialName, $socialUser)
    {
        $connectedAccount = ConnectedAccount::query()->where('provider', '=', $socialName)
            ->where('provider_id', '=', $socialUser->getId())->first();
        if ($connectedAccount) {
            return $this->user->find($connectedAccount->user_id);
        }

        $user = null;
        $email = $socialUser->getEmail();
        if ($email) {
            $user = $this->user->findByAttributes(['email' => $email]);

            if ($user) {
                // Tạo bản ghi cho connected account
                $connectAccountData = [
                    'user_id' => $user->id,
                    'provider' => $socialName,
                    'provider_id' => $socialUser->getId(),
                    'nickname' => $socialUser->getNickname(),
                    'name' => $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(),
                    'raw' => serialize($socialUser->getRaw())
                ];
                ConnectedAccount::query()->create($connectAccountData);
            }
        }

        return $user;
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasVerifiedEmail()) {
            $this->guard()->logout();

            $request->session()->invalidate();
            return redirect()->route('login')->withInput()->withErrors(['email' => trans('user::auth.required_verify_email')]);
        }
        app(UserRepository::class)->update($user, ['last_login' => date('Y-m-d H:i:s')]);
        return redirect()->intended($this->redirectPath());
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return response()->json(['msg' => 'Logout successful!']);
        }
        return redirect()->route('home')->withSuccess(__('Logout successful!'));
    }
}
