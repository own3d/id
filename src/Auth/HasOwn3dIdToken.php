<?php

namespace Own3d\Id\Auth;

use Illuminate\Foundation\Auth\User;

/**
 * @mixin User
 * @author René Preuß <rene.p@own3d.tv>
 */
trait HasOwn3dIdToken
{
    /**
     * @var string|null
     */
    protected $own3dIdToken;

    public function getOwn3dIdToken(): ?string
    {
        return $this->own3dIdToken;
    }

    public function withOwn3dIdToken($decoded): self
    {
        $this->own3dIdToken = $decoded;

        return $this;
    }
}
