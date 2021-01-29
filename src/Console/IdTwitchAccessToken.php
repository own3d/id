<?php

namespace Own3d\Id\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Own3d\Id\Exceptions\RequestRequiresClientIdException;
use Own3d\Id\Own3dId;
use Own3d\Id\Repository\AppTokenRepository;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Only intended for first-party apps.
 *
 * @internal this requires trusted oauth client credentials
 *
 * @author René Preuß <rene.p@own3d.tv>
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
     *
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     * @throws InvalidArgumentException
     *
     * @return int
     */
    public function handle(Own3dId $own3dId): int
    {
        /** @var AppTokenRepository $repository */
        $repository = app(AppTokenRepository::class);

        $result = $own3dId
            ->withToken($repository->getAccessToken())
            ->post('/api/app-access-tokens/twitch');

        if ( ! $result->success()) {
            $this->error('Token failed to update.');

            return 1;
        }

        Cache::store(config('twitch-api.oauth_client_credentials.cache_store'))
            ->set(config('twitch-api.oauth_client_credentials.cache_key'), [
                'access_token' => $result->data()->access_token,
                'refresh_token' => $result->data()->refresh_token ?? null,
                'expires_in' => $result->data()->expires_in,
                'scope' => $result->data()->scopes,
                'token_type' => $result->data()->token_type,
                'expires_at' => $result->data()->expires_at,
            ]);

        $this->info('Token successfully updated.');

        return 0;
    }
}
