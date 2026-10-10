<?php

namespace Abolaradev\Authora\Contracts;

interface ShouldSendNotification
{
    /**
     * Send a notification to the user.
     */
    public function send();
}