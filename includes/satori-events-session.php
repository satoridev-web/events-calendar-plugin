<?php

/**
 * Session Handling for Events Calendar Plugin
 *
 * Ensures PHP session is safely started (for flash messaging, etc.)
 *
 * @package Satori_EC
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

// ------------------------------------------------------------------------------
// INIT: Start PHP session early if needed
// ------------------------------------------------------------------------------
add_action('init', __NAMESPACE__ . '\\satori_events_maybe_start_session', 1);

/**
 * Safely start a PHP session if one isn’t already active.
 */
function satori_events_maybe_start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
