<?php

namespace Own3d\Id\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Own3d\Id\Contracts\HasOwn3dAccessToken;
use Own3d\Id\Helpers\Paginator;
use Own3d\Id\Own3dId as Own3dIdService;
use Own3d\Id\Result;

/**
 * @author René Preuß <rene.p@own3d.tv>
 *
 * @method static void routes(array $options = [])
 * @method static bool shouldRunMigrations()
 * @method static Own3dIdService ignoreMigrations()
 * @method static Own3dIdService registerMigrations()
 * @method static void setBaseUrl(string $baseUrl)
 * @method static void setAuthBaseUrl(string $authBaseUrl)
 * @method static void useUserModel(string $userModel)
 * @method static Model user()
 *
 * @method static string getClientId()
 * @method static void setClientId(string $clientId)
 * @method static Own3dIdService withClientId(string $clientId)
 * @method static string getClientSecret()
 * @method static void setClientSecret(string $clientSecret)
 * @method static Own3dIdService withClientSecret(string $clientSecret)
 * @method static string getRedirectUri()
 * @method static void setRedirectUri(string $redirectUri)
 * @method static Own3dIdService withRedirectUri(string $redirectUri)
 * @method static string getToken()
 * @method static void setToken(string $token)
 * @method static Own3dIdService withToken(string $token)
 * @method static Own3dIdService actingAs(HasOwn3dAccessToken $user)
 *
 * @method static Result get(string $path = '', array $parameters = [], ?Paginator $paginator = null)
 * @method static Result post(string $path = '', array $parameters = [], ?Paginator $paginator = null)
 * @method static Result put(string $path = '', array $parameters = [], ?Paginator $paginator = null)
 * @method static Result delete(string $path = '', array $parameters = [], ?Paginator $paginator = null)
 * @method static Result json(string $method, string $path = '', ?array $body = null)
 * @method static Result query(string $method = 'GET', string $path = '', array $parameters = [], ?Paginator $paginator = null, mixed $jsonBody = null)
 * @method static string buildQuery(array $query)
 *
 * @method static Result getAuthedUser()
 * @method static Result setAuthedUserEmailAddress(string $email)
 * @method static Result getUserById(string $id)
 * @method static Result getUsersByEmail(string $email)
 * @method static Result getUserConnections(array $parameters = [])
 * @method static Result getUserConnectionByPlatformId(string $platform, string $id)
 *
 * @method static Result retrievingToken(string $grantType, array $attributes)
 *
 * @method static Result sendEvent(string $type, array $data, string $version)
 *
 * @see Own3dIdService
 */
class Own3dId extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Own3dIdService::class;
    }
}
