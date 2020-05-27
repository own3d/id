<?php

namespace Own3d\Id\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Own3d\Id\Http\Controllers\Controller;
use Own3d\Id\Socialite\Provider;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver(Provider::IDENTIFIER)->stateless()->redirect();
    }

    public function callback()
    {
        $model = config('own3d-id.model');
        $userSocial = Socialite::driver(Provider::IDENTIFIER)->stateless()->user();
        /** @var User $user */
        $user = $model::where(['own3d_id' => $userSocial->getId()])->first();

        $attributes = [
            'name' => $userSocial->getName(),
            'email' => $userSocial->getEmail(),
            'own3d_id' => $userSocial->getId(),
            'own3d_user' => $userSocial->user,
        ];

        if (!$user) {
            $user = $model::create($attributes);
        } else {
            $user->forceFill($attributes)->save();
        }

        $this->guard()->login($user);
        return redirect()->route('home');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}