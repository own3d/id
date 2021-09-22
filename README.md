# OWN3D ID

PHP OWN3D ID API Client for Laravel 5+

## Table of contents

1. [Installation](#installation)
2. [Remarks](#remarks)
3. [Socialite Event Listener](#socialite-event-listener)
4. [Configuration](#configuration)
5. [Examples](#examples)
6. [Documentation](#documentation)
7. [Development](#Development)

## Installation

Install the own3d id package with composer:

```
composer require own3d/id
```

## Remarks

In the StreamTV / OWN3D ID Library all SSO IDs are defined as strings. This comes from the origin that all IDs should become UUIDs. We will simply continue the id assignment on big-integers, since we never implemented this step. We recommend to store all ids as big-integers (20) in your database. It is not guaranteed that we will assign IDs incrementally.

## Socialite Event Listener

- Add `SocialiteProviders\Manager\SocialiteWasCalled` event to your `listen[]` array in `app/Providers/EventServiceProvider`.
- Add your listeners (i.e. the ones from the providers) to the `SocialiteProviders\Manager\SocialiteWasCalled[]` that you just created.
- The listener that you add for this provider is `'Own3d\\Id\\Socialite\\Own3dIdExtendSocialite@handle',`.
- Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

```php
use SocialiteProviders\Manager\SocialiteWasCalled;
use Own3d\Id\Socialite\Own3dIdExtendSocialite;

/**
 * The event handler mappings for the application.
 *
 * @var array
 */
protected $listen = [
    SocialiteWasCalled::class => [
        // add your listeners (aka providers) here
        Own3dIdExtendSocialite::class,
    ],
];
```

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider="Own3d\Id\Providers\Own3dIdServiceProvider"
```

Add environmental variables to your `.env`

```
OWN3D_ID_KEY=
OWN3D_ID_SECRET=
OWN3D_ID_REDIRECT_URI=${APP_URL}/login/callback
```

You will need to add an entry to the services configuration file so that after config files are cached for usage in production environment (Laravel command `artisan config:cache`) all config is still available.

**Add to `config/services.php`:**

```php
'own3d-id' => [
    'client_id' => env('OWN3D_ID_KEY'),
    'client_secret' => env('OWN3D_ID_SECRET'),
    'redirect' => env('OWN3D_ID_REDIRECT_URI')
],
```

## Using Own3d ID ad Primary Login Service

1. Migrate the database
2. Update user model
3. Update web routes

```php
use Own3d\Id\Traits\Own3dIdUser;

class User {
    use Own3dIdUser;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'own3d_user' => 'array',
        'own3d_id' => 'int'
    ];
}
```

```php
use Own3d\Id\Own3dId;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Own3dId::routes();

// route 'home' is required
Route::get('/home', 'HomeController@index')->name('home');
```

## Enable OWN3D ID Migrations

If you want to use the own3d-migrations for you project, you may enable it within the `AppServiceProvider`.

```php
use Own3d\Id\Own3dId;

public function register(): void
{
    Own3dId::registerMigrations();
}
```

## Examples

#### Basic

```php
$own3dId = new Own3d\Id\Own3dId();

$own3dId->setClientId('abc123');

...
```

#### Setters

```php
$own3dId = new Own3d\Id\Own3dId();

$own3dId->setClientId('abc123');
$own3dId->setClientSecret('abc456');
$own3dId->setToken('abcdef123456');

$own3dId = $own3dId->withClientId('abc123');
$own3dId = $own3dId->withClientSecret('abc123');
$own3dId = $own3dId->withToken('abcdef123456');
```

#### OAuth Tokens

```php
$own3dId = new Own3d\Id\Own3dId();

$own3dId->setClientId('abc123');
$own3dId->setToken('abcdef123456');

$result = $own3dId->getAuthedUser();

$user = $userResult->shift();
```

```php
$own3dId->setToken('uvwxyz456789');

$result = $own3dId->getAuthedUser();
```

```php
$result = $own3dId->withToken('uvwxyz456789')->getAuthedUser();
```

#### Facade

```php
use Own3d\Id\Facades\Own3dId;

Own3dId::withClientId('abc123')->withToken('abcdef123456')->getAuthedUser();
```

## Documentation

### Oauth

```php
public function retrievingToken(string $grantType, array $attributes)
```

### Users

```php
public function getAuthedUser()
public function getUserById(string $id)
public function getUserConnections(array $parameters = [])
public function getUserConnectionByPlatformId(string $platform, string $id)
```

### Events

```php
public function sendEvent(string $type, array $data, string $version)
```

[**OAuth Scopes Enums**](https://bitbucket.org/own3dtv/own3d-id/src/master/src/Enums/Scope.php)

## Development

#### Run Tests

```shell
composer test
```

```shell
BASE_URL=xxxx CLIENT_ID=xxxx CLIENT_KEY=yyyy CLIENT_ACCESS_TOKEN=zzzz composer test
```

#### Generate Documentation

```shell
composer docs
```
