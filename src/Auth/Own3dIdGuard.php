<?php

namespace Own3d\Id\Auth;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use stdClass;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dIdGuard
{
    public const RSA_KEY_PATTERN =
        '/^(-----BEGIN (RSA )?(PUBLIC|PRIVATE) KEY-----)\R.*(-----END (RSA )?(PUBLIC|PRIVATE) KEY-----)\R?$/s';

    /**
     * @var UserProvider
     */
    protected UserProvider $userProvider;

    /**
     * The secrets of the OWN3D ID guard.
     *
     * @var array
     */
    private static array $extSecrets = [];

    /**
     * @var callable[]
     */
    private static array $rsaKeyLoaders = [];

    /**
     * Create a new authentication guard.
     *
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Adds a secret for the OWN3D ID guard.
     *
     * @param string $secret
     */
    public static function addExtSecret(string $secret): void
    {
        static::$extSecrets[] = ['n' => base64_decode($secret), 'alg' => 'HS256'];
    }

    /**
     * Adds a secret for the OWN3D ID guard.
     *
     * @param string $key
     * @param string $alg
     */
    public static function addRsaSecret(string $key, string $alg = 'RS256'): void
    {
        if ($rsaMatch = preg_match(static::RSA_KEY_PATTERN, $key)) {
            static::$extSecrets[] = ['n' => $key, 'alg' => $alg];
        } elseif (false === $rsaMatch) {
            throw new RuntimeException(sprintf('PCRE error [%d] encountered during key match attempt', preg_last_error()));
        }
    }

    public static function addRsaKeyLoader(callable $rsaKeyLoader): void
    {
        self::$rsaKeyLoaders[] = $rsaKeyLoader;
    }

    private static function callRsaKeyLoaders(): void
    {
        foreach (self::$rsaKeyLoaders as $rsaKeyLoader) {
            /** @var string[] $rsaSecrets */
            $rsaSecrets = ($rsaKeyLoader)();
            foreach ($rsaSecrets as $rsaSecret) {
                self::addRsaSecret($rsaSecret);
            }
        }
    }

    public function user(Request $request): ?User
    {
        if (!($token = $request->headers->get('Authorization'))) {
            return null;
        }

        if (!Str::startsWith($token, config('own3d-id.token_type'))) {
            return null;
        }

        if (empty(static::$extSecrets)) {
            self::callRsaKeyLoaders();
        }

        try {
            $token = explode(' ', $token)[1] ?? null;
            $decoded = $this->decodeAuthorizationToken($token);

            return $this->resolveUser($decoded);
        } catch (Exception $exception) {
            return null;
        }
    }

    private function decodeAuthorizationToken(string $token): stdClass
    {
        foreach (self::$extSecrets as $extSecret) {
            try {
                return JWT::decode($token, new Key($extSecret['n'], $extSecret['alg']));
            } catch (SignatureInvalidException|ExpiredException $exception) {
                // do nothing
            }
        }

        throw new SignatureInvalidException('OWN3D ID signature verification failed.');
    }

    /**
     * Registers the OWN3D ID guard as new auth guard.
     *
     * Add this to your AuthServiceProvider::boot() method.
     *
     * @param string $driver
     */
    public static function register(string $driver = 'own3d-id'): void
    {
        self::addRsaKeyLoader(fn() => [file_get_contents(__DIR__ . '/../../oauth-public.key')]);
        Auth::extend($driver, static function ($app, $name, array $config) {
            return new RequestGuard(static function ($request) use ($config) {
                return (new self(
                    Auth::createUserProvider($config['provider'])
                ))->user($request);
            }, app('request'));
        });
    }

    private function resolveUser(stdClass $decoded): ?User
    {
        // todo create own user provider soon for this class and socialite controller
        /** @var User $user */
        $user = $this->userProvider->retrieveById($decoded->sub);

        if (null === $user) {
            return null;
        }

        if (method_exists($user, 'withOwn3dIdToken')) {
            $user = $user->withOwn3dIdToken($decoded);
        }

        if (method_exists($user, 'withOwn3dAccessToken')) {
            $user = $user->withOwn3dAccessToken($decoded);
        }

        return $user;
    }
}
