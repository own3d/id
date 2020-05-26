<?php

use Own3d\Id\Permission\Permission;

/**
 * @property int own3d_permissions
 */
class HasOwn3dIdPermissions
{
    public function hasOwn3dPermission($flag): bool
    {
        return (($this->own3d_permissions & Permission::ADMINISTRATOR) === Permission::ADMINISTRATOR) || (($this->own3d_permissions & $flag) === $flag);
    }
}