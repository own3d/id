<?php

namespace Own3d\Id\Enums;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Scope
{
    /**
     * View a user's academic information.
     */
    public const ACADEMY_READ = 'academy:read';

    /**
     * Manage a user's academic information.
     */
    public const ACADEMY_MANAGE = 'academy:manage';

    /**
     * Manage a user's OWN3D Pro & Free Alerts Extension alerts.
     */
    public const ALERTS_MANAGE = 'alerts:manage';

    /**
     * Manage a user's affiliate configuration.
     */
    public const AFFILIATE_MANAGE = 'affiliate:manage';

    /**
     * View a user's analytics data.
     */
    public const ANALYTICS_READ = 'analytics:read';

    /**
     * View a user's chatbot configurations.
     */
    public const CHATBOT_READ = 'chatbot:read';

    /**
     * Manage a user's chatbot configuration, like commands, moderation, timers, giveaways.
     */
    public const CHATBOT_MANAGE = 'chatbot:manage';

    /**
     * Manage a user's chatbot commands configuration.
     */
    public const CHATBOT_MANAGE_COMMANDS = 'chatbot:manage:commands';

    /**
     * Manage a user's chatbot moderation configuration.
     */
    public const CHATBOT_MANAGE_MODERATION = 'chatbot:manage:moderation';

    /**
     * Manage a user's chatbot timers configuration.
     */
    public const CHATBOT_MANAGE_TIMERS = 'chatbot:manage:timers';

    /**
     * Manage a user's chatbot giveaways configuration.
     */
    public const CHATBOT_MANAGE_GIVEAWAYS = 'chatbot:manage:giveaways';

    /**
     * View a user's connected social accounts, like twitch, discord, etc.
     */
    public const CONNECTIONS = 'connections';

    /**
     * View a user's donations.
     */
    public const DONATIONS_READ = 'donations:read';

    /**
     * Manage a user's donations.
     */
    public const DONATIONS_MANAGE = 'donations:manage';

    /**
     * View a user's entitlements aka. purchased products.
     */
    public const ENTITLEMENTS_READ = 'entitlements:read';

    /**
     * View a user's OWN3D extensions.
     */
    public const EXTENSIONS_READ = 'extensions:read';

    /**
     * Manage a user's OWN3D extensions (eg. change configurations).
     */
    public const EXTENSIONS_MANAGE = 'extensions:manage';

    /**
     * Manage a user's linkspree.
     */
    public const LINKSPREE_MANAGE = 'linkspree:manage';

    /**
     * View a user's OWN3D & OWN3D Pro orders.
     */
    public const ORDERS_READ = 'orders:read';

    /**
     * Manage a user's OWN3D & OWN3D Pro orders, like refund.
     */
    public const ORDERS_MANAGE = 'orders:manage';

    /**
     * View a user's OWN3D Pro subscription.
     */
    public const SUBSCRIPTION_READ = 'subscription:read';

    /**
     * 	View a user's information.
     */
    public const USER_READ = 'user:read';

    /**
     * Manage a user's information, like name.
     */
    public const USER_MANAGE = 'user:manage';

    /**
     * Manage a user's credentials, like email, password or even connections.
     */
    public const USER_MANAGE_CREDENTIALS = 'user:manage:credentials';

    /**
     * Manage a user's OWN3D Pro widgets.
     */
    public const WIDGETS_MANAGE = 'widgets:manage';
}
