<?php


namespace Own3d\Id\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Own3d\Id\Exceptions\RequestRequiresClientIdException;
use Own3d\Id\Own3dId;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @author René Preuß <rene.p@own3d.tv>
 *
 * Only intended for first-party apps.
 *
 * @internal This requires trusted oauth client credentials.
 */
class IdTwitchAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'id:twitch-access-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls the twitch app access token from our id service.';

    /**
     * Execute the console command.
     *
     * @param Own3dId $own3dId
     * @return int
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     * @throws InvalidArgumentException
     */
    public function handle(Own3dId $own3dId): int
    {
        $result = $own3dId->post('/api/app-access-tokens/twitch');

        if (!$result->success()) {
            return 1;
        }

        Cache::store(config('twitch-api.oauth_client_credentials.cache_store'))
            ->set(config('twitch-api.oauth_client_credentials.cache_key'), $result->data());

        return 0;
    }
}
