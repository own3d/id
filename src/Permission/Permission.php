<?php

namespace Own3d\Id\Permission;

class Permission
{
    // 1, 2, 4, 8
    public const ADMINISTRATOR = 1 << 0;
    public const MANAGE_CDN = 1 << 1;
    public const MANAGE_SHOP = 1 << 2;
    public const VIEW_LOGS = 1 << 3;
    public const VIEW_KPIS = 1 << 4;
    public const MANAGE_ROLES = 1 << 5;
    public const MANAGE_USERS = 1 << 6;
}
