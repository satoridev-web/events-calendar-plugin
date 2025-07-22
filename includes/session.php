<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Safely start a PHP session (if not already active)
 */
function ec_maybe_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'ec_maybe_start_session', 1); // Early priority
