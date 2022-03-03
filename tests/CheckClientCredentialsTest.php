<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

use Illuminate\Http\Request;
use Own3d\Id\Enums\Scope;
use Own3d\Id\Exceptions\MissingScopeException;
use Own3d\Id\Http\Middleware\CheckClientCredentials;
use Own3d\Id\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class CheckClientCredentialsTest extends ApiTestCase
{
    public function testValidScopes()
    {
        $request = new Request();

        $request->merge([
            'title' => 'Title is in Mixed Case',
        ]);

        $request->headers->set('Authorization', $this->generateBearerToken([Scope::USER_READ, Scope::CONNECTIONS]));

        $middleware = new CheckClientCredentials();

        $middleware->handle($request, function (Request $request) {
            $this->assertEquals('Title is in Mixed Case', $request->get('title'));

            self::assertNotEmpty($request->attributes->get('oauth_access_token_id'));
            self::assertEquals($this->getClientId(), $request->attributes->get('oauth_client_id'));
            self::assertFalse($request->attributes->get('oauth_client_trusted'));
            self::assertNotNull($request->attributes->get('oauth_user_id'));
            self::assertIsArray($request->attributes->get('oauth_scopes'));
        }, Scope::CONNECTIONS);
    }

    public function testInvalidScopes()
    {
        $request = new Request();

        $request->merge([
            'title' => 'Title is in Mixed Case',
        ]);

        $request->headers->set('Authorization', $this->generateBearerToken([Scope::CONNECTIONS]));

        $middleware = new CheckClientCredentials();

        try {
            $middleware->handle($request, function (Request $request) {
                fail('Invalid scopes has been accepted.');
            }, Scope::USER_READ);
        } catch (MissingScopeException $exception) {
            self::assertEquals(
                'Invalid scope(s) provided. (Missing Scopes: [user:read], Provided Scopes: [connections], Condition: all)',
                $exception->getMessage()
            );
        }
    }

    public function testWildcardScope()
    {
        $request = new Request();

        $request->merge([
            'title' => 'Title is in Mixed Case',
        ]);

        $request->headers->set('Authorization', $this->generateBearerToken(['*']));

        $middleware = new CheckClientCredentials();

        $middleware->handle($request, function (Request $request) {
            $this->assertEquals('Title is in Mixed Case', $request->get('title'));
        }, Scope::CONNECTIONS);
    }

    private function generateBearerToken(array $scopes): string
    {
        $this->getClient()->setClientSecret($this->getClientSecret());

        $this->registerResult($result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => implode(' ', $scopes),
        ]));

        $data = $result->data();

        self::assertEquals('Bearer', $data->token_type);

        return sprintf('%s %s', $data->token_type, $data->access_token);
    }
}
