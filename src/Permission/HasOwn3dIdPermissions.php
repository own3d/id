<?php

namespace Own3d\Id\Permission;

/**
 * @property int own3d_permissions
 */
trait HasOwn3dIdPermissions
{
    public function hasOwn3dPermission($flag): bool
    {
        return (($this->own3d_permissions & Permission::ADMINISTRATOR) === Permission::ADMINISTRATOR) || (($this->own3d_permissions & $flag) === $flag);
    }

    public function getOwn3dPermissionsAttribute(): int
    {
        return $this->own3d_user['permissions'];
    }
}
