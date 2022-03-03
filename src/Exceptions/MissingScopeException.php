<?php

namespace Own3d\Id\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;

class MissingScopeException extends AuthorizationException
{
    public const CONDITION_UNKNOWN = 'unknown';
    public const CONDITION_ALL = 'all';
    public const CONDITION_ANY = 'any';

    /**
     * The scopes that the user did not have.
     *
     * @var array
     */
    protected array $scopes;

    private array $providedScopes;
    private ?string $condition;

    /**
     * Create a new missing scope exception.
     *
     * @param array|string $scopes
     * @param string $message
     * @param array|string $providedScopes
     * @param string|null $condition
     *
     */
    public function __construct(
        $scopes = [],
        string $message = 'Invalid scope(s) provided.',
        $providedScopes = [],
        ?string $condition = null
    )
    {
        $this->scopes = Arr::wrap($scopes);
        $this->providedScopes = Arr::wrap($providedScopes);
        $this->condition = $condition;

        parent::__construct(
            sprintf('%s (%s)', $message, $this->getDebugMessage())
        );
    }

    /**
     * Get the scopes that the user did not have.
     */
    public function scopes(): array
    {
        return $this->scopes;
    }

    /**
     * Get the scopes that the user provided.
     */
    public function providedScopes(): array
    {
        return $this->providedScopes;
    }

    private function getDebugMessage(): string
    {
        return sprintf(
            'Missing Scopes: [%s], Provided Scopes: [%s], Condition: %s',
            implode(', ', $this->scopes),
            implode(', ', $this->providedScopes),
            $this->getAuthorizationCondition()
        );
    }

    private function getAuthorizationCondition(): string
    {
        if (!empty($this->condition)) {
            return $this->condition;
        }

        return self::CONDITION_UNKNOWN;
    }
}
