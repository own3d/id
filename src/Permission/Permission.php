<?php

namespace Own3d\Id\Permission;

class Permission
{
    // administration
    public const ADMINISTRATOR = 1 << 0;

    // features
    public const MANAGE_CDN = 1 << 1;
    public const MANAGE_SHOP = 1 << 2;
    public const VIEW_LOGS = 1 << 3;
    public const VIEW_KPIS = 1 << 4;
    public const MANAGE_ROLES = 1 << 5;
    public const MANAGE_USERS = 1 << 6;
    public const VIEW_ADDRESSES = 1 << 7;
    public const MANAGE_FULFILLMENT = 1 << 8;

    // functions
    public const STAFF = 1 << 9;
    public const MARKETING = 1 << 10;
    public const SUPPORT = 1 << 11;

    // aliases
    public const MANAGER = self::ADMINISTRATOR;
    public const SHOP_ADMIN = self::MANAGE_SHOP;
}
