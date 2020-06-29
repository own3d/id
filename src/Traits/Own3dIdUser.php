<?php

namespace Own3d\Id\Traits;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * @author René Preuß <rene.p@own3d.tv>
 * @property string own3d_id
 * @property array own3d_user
 */
trait Own3dIdUser
{
    public function getOwn3dAccessToken(): ?string
    {
        return $this->own3d_user['oauth']['access_token'] ?? null;
    }

    public function getOwn3dRefreshToken(): ?string
    {
        return $this->own3d_user['oauth']['refresh_token'] ?? null;
    }

    public function getOwn3dTokenExpiresAt(): ?CarbonInterface
    {
        if ($issuedAt = $this->getOwn3dTokenIssuedAt()) {
            return $issuedAt->addSeconds($this->own3d_user['oauth']['expires_in']);
        }

        return null;
    }

    public function getOwn3dTokenIssuedAt(): ?CarbonInterface
    {
        if (isset($this->own3d_user['oauth']['issued_at'])) {
            return Carbon::make($this->own3d_user['oauth']['issued_at']);
        }

        return null;
    }
}
