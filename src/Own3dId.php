<?php

namespace Own3d\Id;

use Illuminate\Support\Facades\Route;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dId
{
    public static function routes($options = [])
    {
        Route::middleware($options['middleware'] ?? 'web')
            ->namespace('Own3d\Id\Http\Controllers')
            ->group(function () {
                Route::get('login', 'Auth\SocialiteController@redirect')->name('login');
                Route::get('login/callback', 'Auth\SocialiteController@callback');
            });
    }
}