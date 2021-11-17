<?php

namespace Own3d\Id\Enums;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Scope
{
    /**
     * Manage a user's OWN3D Pro & Free Alerts Extension alerts.
     */
    const ALERTS_MANAGE = 'alerts:manage';

    /**
     * Manage a user's affiliate configuration.
     */
    const AFFILIATE_MANAGE = 'affiliate:manage';

    /**
     * View a user's analytics data.
     */
    const ANALYTICS_READ = 'analytics:read';

    /**
     * View a user's chatbot configurations.
     */
    const CHATBOT_READ = 'chatbot:read';

    /**
     * Manage a user's chatbot configuration, like commands, moderation, timers, giveaways.
     */
    const CHATBOT_MANAGE = 'chatbot:manage';

    /**
     * Manage a user's chatbot commands configuration.
     */
    const CHATBOT_MANAGE_COMMANDS = 'chatbot:manage:commands';

    /**
     * Manage a user's chatbot moderation configuration.
     */
    const CHATBOT_MANAGE_MODERATION = 'chatbot:manage:moderation';

    /**
     * Manage a user's chatbot timers configuration.
     */
    const CHATBOT_MANAGE_TIMERS = 'chatbot:manage:timers';

    /**
     * Manage a user's chatbot giveaways configuration.
     */
    const CHATBOT_MANAGE_GIVEAWAYS = 'chatbot:manage:giveaways';

    /**
     * View a user's connected social accounts, like twitch, discord, etc.
     */
    const CONNECTIONS = 'connections';

    /**
     * View a user's donations.
     */
    const DONATIONS_READ = 'donations:read';

    /**
     * Manage a user's donations.
     */
    const DONATIONS_MANAGE = 'donations:manage';

    /**
     * View a user's entitlements aka. purchased products.
     */
    const ENTITLEMENTS_READ = 'entitlements:read';

    /**
     * View a user's OWN3D extensions.
     */
    const EXTENSIONS_READ = 'extensions:read';

    /**
     * Manage a user's OWN3D extensions (eg. change configurations).
     */
    const EXTENSIONS_MANAGE = 'extensions:manage';

    /**
     * Manage a user's linkspree.
     */
    const LINKSPREE_MANAGE = 'linkspree:manage';

    /**
     * View a user's OWN3D & OWN3D Pro orders.
     */
    const ORDERS_READ = 'orders:read';

    /**
     * Manage a user's OWN3D & OWN3D Pro orders, like refund.
     */
    const ORDERS_MANAGE = 'orders:manage';

    /**
     * View a user's OWN3D Pro subscription.
     */
    const SUBSCRIPTION_READ = 'subscription:read';

    /**
     * 	View a user's information.
     */
    const USER_READ = 'user:read';

    /**
     * Manage a user's information, like name.
     */
    const USER_MANAGE = 'user:manage';

    /**
     * Manage a user's credentials, like email, password or even connections.
     */
    const USER_MANAGE_CREDENTIALS = 'user:manage:credentials';

    /**
     * Manage a user's OWN3D Pro widgets.
     */
    const WIDGETS_MANAGE = 'widgets:manage';
}
