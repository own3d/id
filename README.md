# OWN3D ID

PHP OWN3D ID API Client for Laravel 5+

## Table of contents

1. [Installation](#installation)
2. [Event Listener](#event-listener)
3. [Configuration](#configuration)
4. [Examples](#examples)
5. [Documentation](#documentation)
6. [Development](#Development)

## Installation

```
composer require own3d/id
```

**If you use Laravel 5.5+ you are already done, otherwise continue.**

Add Service Provider to your `app.php` configuration file:

```php
Own3d\Id\Providers\Own3dIdServiceProvider::class,
```

## Event Listener

- Add `SocialiteProviders\Manager\SocialiteWasCalled` event to your `listen[]` array in `app/Providers/EventServiceProvider`.
- Add your listeners (i.e. the ones from the providers) to the `SocialiteProviders\Manager\SocialiteWasCalled[]` that you just created.
- The listener that you add for this provider is `'Own3d\\Id\\Socialite\\Own3dIdExtendSocialite@handle',`.
- Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.


```
/**
 * The event handler mappings for the application.
 *
 * @var array
 */
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // add your listeners (aka providers) here
        'Own3d\\Id\\Socialite\\Own3dIdExtendSocialite@handle',
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
OWN3D_ID_REDIRECT_URI=${APP_URI}/login/callback
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

## Examples

#### Basic

```php
$own3dId = new Own3d\Id\OwnedId();

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