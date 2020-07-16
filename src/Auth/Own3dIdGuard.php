<?php

namespace Own3d\Id\Auth;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\RequestGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RuntimeException;
use stdClass;
use function preg_last_error;
use function preg_match;
use function sprintf;

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
     * @var array
     */
    private static array $extSecrets = [];

    /**
     * @var Closure
     */
    private static Closure $rsaKeyLoader;

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
     * @param string $keyPath
     */
    public static function addRsaSecret(string $keyPath): void
    {
        if ($rsaMatch = preg_match(static::RSA_KEY_PATTERN, $keyPath)) {
            static::$extSecrets[] = ['n' => $keyPath, 'alg' => 'RS256'];
        } elseif ($rsaMatch === false) {
            throw new RuntimeException(
                sprintf('PCRE error [%d] encountered during key match attempt', preg_last_error())
            );
        }
    }

    public static function setRsaKeyLoader(Closure $rsaKeyLoader): void
    {
        self::$rsaKeyLoader = $rsaKeyLoader;
    }

    private static function callRsaKeyLoader(): string
    {
        return (self::$rsaKeyLoader)();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function user(Request $request)
    {
        if (!($token = $request->headers->get('Authorization'))) {
            return null;
        }

        if (!Str::startsWith($token, 'OAuth')) {
            return null;
        }

        if (empty(static::$extSecrets)) {
            self::addRsaSecret(self::callRsaKeyLoader());
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
                return JWT::decode($token, $extSecret['n'], [$extSecret['alg']]);
            } catch (SignatureInvalidException $exception) {
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
     * @param string $secret
     * @param string $driver
     * @noinspection PhpUnusedParameterInspection
     */
    public static function register(string $secret, $driver = 'own3d-id'): void
    {
        self::addExtSecret($secret);
        Auth::extend($driver, static function ($app, $name, array $config) {
            return new RequestGuard(static function ($request) use ($config) {
                return (new self(
                    Auth::createUserProvider($config['provider'])
                ))->user($request);
            }, app('request'));
        });
    }

    /**
     * @param stdClass $decoded
     * @return HasOwn3dIdToken|Builder|Model|object|null
     */
    private function resolveUser(stdClass $decoded)
    {
        // todo create own user provider soon for this class and socialite controller
        $model = config('own3d-id.model');
        /** @var User $user */
        $user = $model::where(['own3d_id' => $decoded->sub])->first();

        if ($user === null) {
            return null;
        }

        if (method_exists($user, 'withOwn3dIdToken')) {
            $user = $user->withOwn3dIdToken($decoded);
        }

        return $user;
    }
}
