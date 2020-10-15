<?php

namespace Own3d\Id\Traits;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Own3d\Id\Auth\HasOwn3dIdToken;
use Own3d\Id\Permission\HasOwn3dIdPermissions;

/**
 * @author René Preuß <rene.p@own3d.tv>
 * @property string own3d_id
 * @property array own3d_user
 */
trait Own3dIdUser
{
    use HasOwn3dIdToken, HasOwn3dIdPermissions;

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

    public function getOwn3dAvatarUrl(): string
    {
        if (!empty($this->own3d_user['avatar'])) {
            return $this->own3d_user['avatar_url'];
        }

        return 'https://assets.cdn.own3d.tv/production/id/avatars/default.jpg';
    }
}
