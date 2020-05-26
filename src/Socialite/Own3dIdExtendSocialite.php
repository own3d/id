<?php

namespace Own3d\Id\Socialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dIdExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            Provider::IDENTIFIER, __NAMESPACE__ . '\Provider'
        );
    }
}