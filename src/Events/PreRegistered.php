<?php

namespace Own3d\Id\Events;

/**
 * This event will be triggered before the user is created in the database, to handle additional stuff.
 * After successful creation, the Registered event will be called.
 */
class PreRegistered
{
    public array $attributes;

    public function __construct(array &$attributes)
    {
        $this->attributes = &$attributes;
    }
}
