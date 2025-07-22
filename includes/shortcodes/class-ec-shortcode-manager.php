<?php

/**
 * Shortcode Manager
 *
 * Handles registration of all shortcodes for the Events Calendar plugin.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit; // Exit if accessed directly

final class Shortcode_Manager
{
    // ------------------------------------------
    // Array of shortcode class names to register
    // ------------------------------------------
    private $shortcodes = [
        EC_Archive_Shortcode::class,
        // Add other shortcode classes here as required
    ];

    // ------------------------------------------
    // Constructor: hook into 'init' to register all shortcodes
    // ------------------------------------------
    public function __construct()
    {
        add_action('init', [$this, 'register_shortcodes']);
    }

    // ------------------------------------------
    // Register all shortcodes in the $shortcodes array
    //
    // Loops through each shortcode class and calls its static register() method
    // ------------------------------------------
    public function register_shortcodes()
    {
        foreach ($this->shortcodes as $shortcode_class) {
            if (class_exists($shortcode_class) && method_exists($shortcode_class, 'register')) {
                $shortcode_class::register();
            }
        }
    }
}

// ------------------------------------------
// Instantiate shortcode manager to trigger shortcode registration
// ------------------------------------------
new Shortcode_Manager();
