<?php

namespace Otodev\Contracts\Models\Notifications;

interface HasNotifiable
{

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications();

    /**
     * Get the entity's read notifications.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function readNotifications();

    /**
     * Get the entity's unread notifications.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function unreadNotifications();

    /**
     * Get the entity's unread notifications by action.
     *
     * @param string $action
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function unreadNotificationsByAction(string $action);

    /**
     * Send a pin 2fa notification to the user.
     *
     * @param string $pin
     *
     * @return void
     */
    public function sendTwoFactorAuthenticationNotification(string $pin);
}
