<?php

namespace Own3d\Id\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Own3d\Id\Own3dId;

class Own3dSsoUserProvider implements UserProvider
{
    private string $model;
    private Request $request;
    private Own3dId $own3dId;
    private array $fields;
    private ?string $accessTokenField;

    public function __construct(
        Own3dId $own3dId,
        Request $request,
        string $model,
        array $fields,
        ?string $accessTokenField = null
    ) {
        $this->accessTokenField = $accessTokenField;
        $this->fields = $fields;
        $this->own3dId = $own3dId;
        $this->request = $request;
        $this->model = $model;
    }

    public static function register()
    {
        Auth::provider(
            'sso-users',
            function ($app, array $config) {
                return new Own3dSsoUserProvider(
                    $app->make(Own3dId::class),
                    $app->make(Request::class),
                    $config['model'],
                    $config['fields'] ?? [],
                    $config['access_token_field'] ?? null
                );
            }
        );
    }

    /**
     * @param mixed $identifier
     *
     * @return Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        $user = $this->newModelQuery($model)
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();

        if ($user) {
            return $user;
        }

        $token = $this->request->bearerToken();

        $this->own3dId->setToken($token);

        $result = $this->own3dId->getAuthedUser();

        if ( ! $result->success()) {
            return null;
        }

        $attributes = Arr::only((array) $result->data(), $this->fields);
        $attributes[$model->getAuthIdentifierName()] = $result->data->id;

        if ($this->accessTokenField) {
            $attributes[$this->accessTokenField] = $token;
        }

        $user = $this->newModelQuery($model)->create($attributes);

        event(new Registered($user));

        return $user;
    }

    /**
     * Create a new instance of the model.
     *
     * @return Model
     */
    public function createModel(): Model
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class();
    }

    /**
     * Get a new query builder for the model instance.
     *
     * @param Model|null $model
     *
     * @return Builder
     */
    protected function newModelQuery($model = null): Builder
    {
        return is_null($model)
            ? $this->createModel()->newQuery()
            : $model->newQuery();
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // void
    }

    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return false;
    }
}
