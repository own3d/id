<?php

declare(strict_types=1);

namespace Own3d\Id;

use Exception;
use Own3d\Id\Helpers\Paginator;
use Psr\Http\Message\ResponseInterface;
use stdClass;


/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Result
{

    /**
     * Query successfull.
     * @var boolean
     */
    public $success = false;

    /**
     * Guzzle exception, if present.
     * @var null|mixed
     */
    public $exception = null;

    /**
     * Query result data.
     * @var array
     */
    public $data = [];

    /**
     * Total amount of result data.
     * @var integer
     */
    public $total = 0;

    /**
     * Status Code.
     * @var integer
     */
    public $status = 0;

    /**
     * OWN3D ID response pagination cursor.
     * @var null|\stdClass
     */
    public $pagination;

    /**
     * Internal paginator.
     * @var null|Paginator
     */
    public $paginator;

    /**
     * Original Guzzle HTTP Response.
     * @var ResponseInterface|null
     */
    public $response;

    /**
     * Original OWN3D ID instance.
     * @var Own3dId
     */
    public $ownedId;

    /**
     * Constructor,
     *
     * @param ResponseInterface|null   $response HTTP response
     * @param Exception|mixed $exception Exception, if present
     * @param null|Paginator  $paginator Paginator, if present
     */
    public function __construct(?ResponseInterface $response, Exception $exception = null, Paginator $paginator = null)
    {
        $this->response = $response;
        $this->success = $exception === null;
        $this->exception = $exception;
        $this->status = $response ? $response->getStatusCode() : 500;
        $jsonResponse = $response ? @json_decode($response->getBody()->getContents(), false) : null;
        if ($jsonResponse !== null) {
            $this->setProperty($jsonResponse, 'data');
            $this->setProperty($jsonResponse, 'total');
            $this->setProperty($jsonResponse, 'pagination');
            $this->paginator = Paginator::from($this);
        }
    }

    /**
     * Sets a class attribute by given JSON Response Body.
     *
     * @param stdClass    $jsonResponse Response Body
     * @param string      $responseProperty Response property name
     * @param string|null $attribute Class property name
     */
    private function setProperty(stdClass $jsonResponse, string $responseProperty, string $attribute = null): void
    {
        $classAttribute = $attribute ?? $responseProperty;
        if ($jsonResponse !== null && property_exists($jsonResponse, $responseProperty)) {
            $this->{$classAttribute} = $jsonResponse->{$responseProperty};
        } elseif ($responseProperty === 'data') {
            $this->{$classAttribute} = $jsonResponse;
        }
    }

    /**
     * Returns wether the query was successfull.
     * @return bool Success state
     */
    public function success(): bool
    {
        return $this->success;
    }

    /**
     * Get the response data, also available as public attribute.
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Returns the last HTTP or API error.
     * @return string Error message
     */
    public function error(): string
    {
        // TODO Switch Exception response parsing to this->data
        if ($this->exception === null || !$this->exception->hasResponse()) {
            return 'OWN3D ID API Unavailable';
        }
        $exception = (string) $this->exception->getResponse()->getBody();
        $exception = @json_decode($exception);
        if (property_exists($exception, 'message') && !empty($exception->message)) {
            return $exception->message;
        }

        return $this->exception->getMessage();
    }

    /**
     * Shifts the current result (Use for single user/video etc. query).
     * @return mixed Shifted data
     */
    public function shift()
    {
        if (!empty($this->data)) {
            $data = $this->data;

            return array_shift($data);
        }

        return null;
    }

    /**
     * Return the current count of items in dataset.
     * @return int Count
     */
    public function count(): int
    {
        return count((array) $this->data);
    }

    /**
     * Set the Paginator to fetch the first set of results.
     * @return null|Paginator
     */
    public function first(): ?Paginator
    {
        return $this->paginator !== null ? $this->paginator->first() : null;
    }

    /**
     * Set the Paginator to fetch the next set of results.
     * @return null|Paginator
     */
    public function next(): ?Paginator
    {
        return $this->paginator !== null ? $this->paginator->next() : null;
    }

    /**
     * Set the Paginator to fetch the last set of results.
     * @return null|Paginator
     */
    public function back(): ?Paginator
    {
        return $this->paginator !== null ? $this->paginator->back() : null;
    }

    /**
     * Get rate limit information.
     *
     * @param string|null $key Get defined index
     *
     * @return string|array|null
     */
    public function rateLimit(string $key = null)
    {
        if (!$this->response) {
            return null;
        }
        $rateLimit = [
            'limit' => (int) $this->response->getHeaderLine('X-RateLimit-Limit'),
            'remaining' => (int) $this->response->getHeaderLine('X-RateLimit-Remaining'),
            'reset' => (int) $this->response->getHeaderLine('Retry-After'),
        ];
        if ($key === null) {
            return $rateLimit;
        }

        return $rateLimit[$key];
    }

    /**
     * Insert users in data response.
     *
     * @param string $identifierAttribute Attribute to identify the users
     * @param string $insertTo Data index to insert user data
     *
     * @return self
     */
    public function insertUsers(string $identifierAttribute = 'user_id', string $insertTo = 'user'): self
    {
        $data = $this->data;
        $userIds = collect($data)->map(function ($item) use ($identifierAttribute) {
            return $item->{$identifierAttribute};
        })->toArray();
        if (count($userIds) === 0) {
            return $this;
        }
        $users = collect($this->ownedId->getUsersByIds($userIds)->data);
        $dataWithUsers = collect($data)->map(function ($item) use ($users, $identifierAttribute, $insertTo) {
            $item->$insertTo = $users->where('id', $item->{$identifierAttribute})->first();

            return $item;
        });
        $this->data = $dataWithUsers->toArray();

        return $this;
    }

    public function response()
    {
        return $this->response;
    }
}