<?php

namespace Own3d\Id;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Route;
use Own3d\Id\ApiOperations;
use Own3d\Id\Exceptions\RequestRequiresAuthenticationException;
use Own3d\Id\Exceptions\RequestRequiresClientIdException;
use Own3d\Id\Exceptions\RequestRequiresRedirectUriException;
use Own3d\Id\Helpers\Paginator;
use Own3d\Id\Traits;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dId
{
    use Traits\OauthTrait;
    use Traits\UsersTrait;
    use Traits\EventsTrait;

    use ApiOperations\Delete;
    use ApiOperations\Get;
    use ApiOperations\Post;
    use ApiOperations\Put;
    use ApiOperations\Json;

    public static string $baseUrl = 'https://id.stream.tv/api/';

    public static bool $skipMigrations = false;

    /**
     * Guzzle is used to make http requests.
     * @var Client
     */
    protected Client $client;

    /**
     * Paginator object.
     * @var Paginator
     */
    protected Paginator $paginator;

    /**
     * OWN3D ID OAuth token.
     * @var string|null
     */
    protected ?string $token = null;

    /**
     * OWN3D ID client id.
     * @var string|null
     */
    protected ?string $clientId = null;

    /**
     * OWN3D ID client secret.
     * @var string|null
     */
    protected ?string $clientSecret = null;

    /**
     * OWN3D ID OAuth redirect url.
     * @var string|null
     */
    protected ?string $redirectUri = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if ($clientId = config('own3d-id.client_id')) {
            $this->setClientId($clientId);
        }
        if ($clientSecret = config('own3d-id.client_secret')) {
            $this->setClientSecret($clientSecret);
        }
        if ($redirectUri = config('own3d-id.redirect_url')) {
            $this->setRedirectUri($redirectUri);
        }
        if ($redirectUri = config('own3d-id.base_url')) {
            self::setBaseUrl($redirectUri);
        }
        $this->client = new Client([
            'base_uri' => self::$baseUrl,
        ]);
    }

    /**
     * Add routes to automatically handle oauth flow.
     *
     * @param array $options
     */
    public static function routes($options = []): void
    {
        Route::middleware($options['middleware'] ?? 'web')
            ->namespace('\Own3d\Id\Http\Controllers')
            ->group(static function () {
                Route::get('login', 'Auth\SocialiteController@redirect')->name('login');
                Route::post('logout', 'Auth\SocialiteController@logout')->name('logout');
                Route::get('login/callback', 'Auth\SocialiteController@callback');
            });
    }

    /**
     * @param string $baseUrl
     *
     * @internal only for internal and debug purposes.
     */
    public static function setBaseUrl(string $baseUrl): void
    {
        self::$baseUrl = $baseUrl;
    }

    /**
     * Get client id.
     * @return string
     * @throws RequestRequiresClientIdException
     */
    public function getClientId(): string
    {
        if (!$this->clientId) {
            throw new RequestRequiresClientIdException;
        }

        return $this->clientId;
    }

    /**
     * Set client id.
     *
     * @param string $clientId OWN3D ID client id
     *
     * @return void
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * Fluid client id setter.
     *
     * @param string $clientId OWN3D ID client id.
     *
     * @return self
     */
    public function withClientId(string $clientId): self
    {
        $this->setClientId($clientId);

        return $this;
    }

    /**
     * Get client secret.
     * @return string
     * @throws RequestRequiresClientIdException
     */
    public function getClientSecret(): string
    {
        if (!$this->clientSecret) {
            throw new RequestRequiresClientIdException;
        }

        return $this->clientSecret;
    }

    /**
     * Set client secret.
     *
     * @param string $clientSecret OWN3D ID client secret
     *
     * @return void
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Fluid client secret setter.
     *
     * @param string $clientSecret OWN3D ID client secret
     *
     * @return self
     */
    public function withClientSecret(string $clientSecret): self
    {
        $this->setClientSecret($clientSecret);

        return $this;
    }

    /**
     * Get redirect url.
     * @return string
     * @throws RequestRequiresRedirectUriException
     */
    public function getRedirectUri(): string
    {
        if (!$this->redirectUri) {
            throw new RequestRequiresRedirectUriException;
        }

        return $this->redirectUri;
    }

    /**
     * Set redirect url.
     *
     * @param string $redirectUri
     *
     * @return void
     */
    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Fluid redirect url setter.
     *
     * @param string $redirectUri
     *
     * @return self
     */
    public function withRedirectUri(string $redirectUri): self
    {
        $this->setRedirectUri($redirectUri);

        return $this;
    }

    /**
     * Get OAuth token.
     * @return string        OWN3D ID token
     * @return string|null
     * @throws RequestRequiresAuthenticationException
     */
    public function getToken(): string
    {
        if (!$this->token) {
            throw new RequestRequiresAuthenticationException;
        }

        return $this->token;
    }

    /**
     * Set OAuth token.
     *
     * @param string $token OWN3D ID OAuth token
     *
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Fluid OAuth token setter.
     *
     * @param string $token OWN3D ID OAuth token
     *
     * @return self
     */
    public function withToken(string $token): self
    {
        $this->setToken($token);

        return $this;
    }

    /**
     * Fluid OAuth user setter.
     *
     * @param Traits\Own3dIdUser $user OWN3D ID OAuth user
     *
     * @return $this
     */
    public function actingAs(Traits\Own3dIdUser $user): self
    {
        $this->withToken($user->getOwn3dAccessToken());

        return $this;
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function get(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('GET', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function post(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('POST', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function delete(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('DELETE', $path, $parameters, $paginator);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param Paginator|null $paginator
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function put(string $path = '', array $parameters = [], Paginator $paginator = null): Result
    {
        return $this->query('PUT', $path, $parameters, $paginator);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array|null $body
     *
     * @return Result
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function json(string $method, string $path = '', array $body = null): Result
    {
        return $this->query($method, $path, [], null, $body);
    }

    /**
     * Build query & execute.
     *
     * @param string $method HTTP method
     * @param string $path Query path
     * @param array $parameters Query parameters
     * @param Paginator $paginator Paginator object
     * @param mixed|null $jsonBody JSON data
     *
     * @return Result     Result object
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function query(string $method = 'GET', string $path = '', array $parameters = [], Paginator $paginator = null, $jsonBody = null): Result
    {
        if ($paginator !== null) {
            $parameters[$paginator->action] = $paginator->cursor();
        }
        try {
            $response = $this->client->request($method, $path, [
                'headers' => $this->buildHeaders($jsonBody ? true : false),
                'query' => $this->buildQuery($parameters),
                'json' => $jsonBody ?: null,
            ]);
            $result = new Result($response, null, $paginator);
        } catch (RequestException $exception) {
            $result = new Result($exception->getResponse(), $exception, $paginator);
        }
        $result->ownedId = $this;

        return $result;
    }

    /**
     * Build query with support for multiple smae first-dimension keys.
     *
     * @param array $query
     *
     * @return string
     */
    public function buildQuery(array $query): string
    {
        $parts = [];
        foreach ($query as $name => $value) {
            $value = (array)$value;
            array_walk_recursive($value, function ($value) use (&$parts, $name) {
                $parts[] = urlencode($name) . '=' . urlencode($value);
            });
        }

        return implode('&', $parts);
    }

    /**
     * Build headers for request.
     *
     * @param bool $json Body is JSON
     *
     * @return array
     * @throws RequestRequiresClientIdException
     */
    private function buildHeaders(bool $json = false): array
    {
        $headers = [
            'Client-ID' => $this->getClientId(),
            'Accept' => 'application/json',
        ];
        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        if ($json) {
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;
    }
}
