<?php

/**
 * Shortcode Manager
 *
 * Handles registration of all shortcodes for the Events Calendar plugin.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

use Satori_EC\EC_Archive_Shortcode;

defined('ABSPATH') || exit; // Exit if accessed directly

final class Shortcode_Manager
{
    // --------------------------------------------------
    // Array of shortcode class names to register
    // --------------------------------------------------
    private $shortcodes = [
        EC_Archive_Shortcode::class,
        // Add additional shortcode classes here if needed
    ];

    // --------------------------------------------------
    // Constructor: Hook into 'init' to register shortcodes
    // --------------------------------------------------
    public function __construct()
    {
        add_action('init', [$this, 'register_shortcodes']);
    }

    // --------------------------------------------------
    // Loop through all defined shortcode classes and call their register() method
    // --------------------------------------------------
    public function register_shortcodes()
    {
        foreach ($this->shortcodes as $shortcode_class) {
            if (class_exists($shortcode_class) && method_exists($shortcode_class, 'register')) {
                $shortcode_class::register();
            }
        }
    }
}

// Note: This class is instantiated by the main Plugin class — no need to instantiate again here.
