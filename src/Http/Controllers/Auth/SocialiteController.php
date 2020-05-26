<?php

namespace Own3d\Id\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\User;
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

        Auth::login($user);
        return redirect()->route('home');
    }
}