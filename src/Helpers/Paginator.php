<?php

namespace Own3d\Id\Helpers;

use Own3d\Id\Result;
use stdClass;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Paginator
{
    /**
     * OWN3D ID response pagination cursor.
     *
     * @var stdClass|null
     */
    private $pagination;

    /**
     * Next desired action (first, after, before).
     *
     * @var string|null
     */
    public $action = null;

    /**
     * Constructor.
     *
     * @param stdClass|null $pagination OWN3D ID response pagination cursor
     */
    public function __construct(stdClass $pagination = null)
    {
        $this->pagination = $pagination;
    }

    /**
     * Create Paginator from Result object.
     *
     * @param Result $result Result object
     *
     * @return self Paginator object
     */
    public static function from(Result $result): self
    {
        return new self($result->pagination);
    }

    /**
     * Return the current active cursor.
     *
     * @return string OWN3D ID cursor
     */
    public function cursor(): string
    {
        return $this->pagination->cursor;
    }

    /**
     * Set the Paginator to fetch the next set of results.
     *
     * @return self
     */
    public function first(): self
    {
        $this->action = 'first';

        return $this;
    }

    /**
     * Set the Paginator to fetch the first set of results.
     *
     * @return self
     */
    public function next(): self
    {
        $this->action = 'after';

        return $this;
    }

    /**
     * Set the Paginator to fetch the last set of results.
     *
     * @return self
     */
    public function back(): self
    {
        $this->action = 'before';

        return $this;
    }
}
