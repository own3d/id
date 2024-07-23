<?php

namespace Own3d\Id\Enums;

/**
 * Supported oauth providers.
 *
 * @author René Preuß <rene.p@own3d.tv>
 * @author Stefan Ensmann <stefan.e@own3d.tv>
 */
class Platform
{
    public const TWITCH = 'twitch';

    public const DISCORD = 'discord';

    public const GOOGLE = 'google';

    public const SLACK = 'slack';

    public const YOUTUBE = 'youtube';

    // public const FACEBOOK = 'facebook';

    // public const INSTAGRAM = 'instagram';

    // public const KICK = 'kick';

    // public const TIKTOK = 'tiktok';

    // public const TROVO = 'trovo';

    // public const TWITTER = 'twitter';

    /**
     * Returns an array with all supported platforms.
     * 
     * @return string[]
     */
    public static function supported() {
        return [
            self::TWITCH,
            self::DISCORD,
            self::GOOGLE,
            self::SLACK,
            self::YOUTUBE,
        ];
    }
}
