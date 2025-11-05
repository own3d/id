<?php

namespace Own3d\Id\Traits;

use Own3d\Id\Auth\AccessToken;
use Own3d\Id\Exceptions\AuthenticationException;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use UnexpectedValueException;

/**
 * @mixin AccessToken
 */
trait DecodesJwtTokens
{
    /**
     * Attempt to extract and validate a JWT from the request.
     *
     * @throws AuthenticationException
     */
    public static function fromRequest(Request $request): static
    {
        try {
            if (!$jwt = $request->bearerToken()) {
                throw new AuthenticationException('Missing bearer token.');
            }

            // Configure leeway for exp/nbf/iat
            JWT::$leeway = (int)Config::get('own3d-id.leeway', 60);

            [$header, $claims] = self::decodeAndVerify($jwt);

            // Stash for downstream
            $request->attributes->set('jwt.token', $jwt);
            $request->attributes->set('jwt.header', $header);
            $request->attributes->set('jwt.claims', $claims);
            $request->attributes->set('jwt.sub', $claims->sub ?? null);

            return new static($claims);
        } catch (ExpiredException $e) {
            throw new AuthenticationException('Token has expired.');
        } catch (SignatureInvalidException $e) {
            throw new AuthenticationException('Invalid token signature.');
        } catch (UnexpectedValueException $e) {
            throw new AuthenticationException('Invalid token.');
        } catch (Exception $e) {
            // Avoid leaking details; log if desired.
            throw new AuthenticationException('Authentication failed.');
        }
    }

    /**
     * @return array{0:object,1:object} [header, claims]
     */
    private static function decodeAndVerify(string $jwt): array
    {
        // Parse header to get alg/kid quickly
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new UnexpectedValueException('Malformed JWT.');
        }
        $header = json_decode(self::b64url($parts[0]) ?: '{}');
        if (!$header || !isset($header->alg)) {
            throw new UnexpectedValueException('Missing alg in header.');
        }

        // Enforce allowed algorithms
        $allowedAlgs = (array)Config::get('own3d-id.allowed_algs', []);
        if (!in_array($header->alg, $allowedAlgs, true)) {
            throw new UnexpectedValueException('Disallowed alg: ' . $header->alg);
        }

        // Fetch JWKS and parse into KeySet
        $jwks = self::getJwks();
        $keys = JWK::parseKeySet($jwks, $header->alg);

        // Prefer matching kid if present
        if (isset($header->kid) && isset($keys[$header->kid])) {
            $claims = JWT::decode($jwt, $keys[$header->kid]);
            return [$header, $claims];
        }

        // Fallback: try keys sequentially
        foreach ($keys as $key) {
            try {
                $claims = JWT::decode($jwt, $key);
                return [$header, $claims];
            } catch (Exception $e) {
                // continue
            }
        }

        throw new UnexpectedValueException('No JWKS key matched token.');
    }

    private static function getJwks(): array
    {
        $jwksUri = (string)Config::get('own3d-id.jwks_uri');
        if (!$jwksUri) {
            throw new RuntimeException('JWKS URI not configured.');
        }

        $ttl = (int)Config::get('own3d-id.jwks_ttl', 600);
        $cacheKey = 'jwt:jwks:' . md5($jwksUri);

        return Cache::remember($cacheKey, $ttl, function () use ($jwksUri) {
            $resp = Http::acceptJson()->timeout(5)->connectTimeout(3)->get($jwksUri);
            if (!$resp->ok()) {
                throw new RuntimeException('Failed to fetch JWKS: HTTP ' . $resp->status());
            }
            $json = $resp->json();
            if (!is_array($json) || !isset($json['keys']) || !is_array($json['keys'])) {
                throw new UnexpectedValueException('Invalid JWKS document.');
            }
            return $json;
        });
    }

    private static function audMatch($audClaim, array $allowedAud): bool
    {
        if (empty($allowedAud)) {
            return true; // if you want to allow missing config; otherwise false
        }
        if (is_string($audClaim)) {
            return in_array($audClaim, $allowedAud, true);
        }
        if (is_array($audClaim)) {
            foreach ($audClaim as $a) {
                if (in_array($a, $allowedAud, true)) {
                    return true;
                }
            }
        }
        return false;
    }

    private static function b64url(string $b64url): string
    {
        $remainder = strlen($b64url) % 4;
        if ($remainder) {
            $b64url .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($b64url, '-_', '+/')) ?: '';
    }
}
