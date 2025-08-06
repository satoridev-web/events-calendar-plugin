<?php

/**
 * Template Functions
 *
 * Provides future-proof functions to load plugin templates with support for theme overrides.
 *
 * @package Satori_EC
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

// =============================================================================
// Define the relative template folder path inside themes
// =============================================================================
function satori_events_get_template_path(): string
{
    return apply_filters('satori_events_template_path', 'events-calendar-plugin/');
}

// =============================================================================
// satori_events_get_template()
// Load a template file from theme override or fallback to plugin template
//
// @param string $template_name Filename of the template to load.
// @param array  $args          Optional associative array of variables to extract for use in template.
// @param string $template_path Relative path in the theme to look for templates.
// @param string $default_path  Absolute path fallback directory (plugin templates).
// =============================================================================
function satori_events_get_template(string $template_name, array $args = [], string $template_path = '', string $default_path = ''): void
{
    if (! $template_name) {
        return;
    }

    // Use provided template path or default theme override folder
    $template_path = $template_path ?: satori_events_get_template_path();

    // Default plugin templates path
    $default_path = $default_path ?: plugin_dir_path(__DIR__) . 'templates/';

    // Attempt to locate template in theme
    $template = locate_template([
        trailingslashit($template_path) . $template_name,
    ]);

    // Fallback to plugin template if not found in theme
    if (! $template) {
        $template = $default_path . $template_name;
    }

    /**
     * Filter the final template path to load.
     *
     * @param string $template       Full path to template file.
     * @param string $template_name  Template filename.
     * @param array  $args           Passed variables to template.
     * @param string $template_path  Relative theme path.
     * @param string $default_path   Plugin fallback path.
     */
    $template = apply_filters('satori_events_get_template', $template, $template_name, $args, $template_path, $default_path);

    // Extract variables for use inside template safely
    if (! empty($args) && is_array($args)) {
        extract($args, EXTR_SKIP);
    }

    // Include template if it exists
    if (file_exists($template)) {
        include $template;
    } else {
        /**
         * Action hook for missing templates.
         *
         * @param string $template_name Missing template filename.
         */
        do_action('satori_events_template_missing', $template_name);

        // Debug info in WP_DEBUG mode
        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo '<!-- Template not found: ' . esc_html($template_name) . ' -->';
        }
    }
}

// =============================================================================
// satori_events_get_template_part()
// Load partial templates (e.g., content-event-card.php)
// Similar to get_template_part(), with optional variables
//
// @param string $slug Template slug (filename without extension).
// @param string $name Optional additional template part name.
// @param array  $args Optional associative array of variables to extract.
// =============================================================================
function satori_events_get_template_part(string $slug, string $name = '', array $args = []): void
{
    $template_path = satori_events_get_template_path();
    $default_path  = plugin_dir_path(__DIR__) . 'templates/';

    // Build possible template filenames
    $templates = [];

    if ($name) {
        $templates[] = "{$slug}-{$name}.php";
    }
    $templates[] = "{$slug}.php";

    foreach ($templates as $template_name) {
        // Locate template in theme
        $template = locate_template([
            trailingslashit($template_path) . $template_name,
        ]);

        // Fallback to plugin template if not found
        if (! $template) {
            $template = $default_path . $template_name;
        }

        if (file_exists($template)) {
            // Extract variables for partial template
            if (! empty($args) && is_array($args)) {
                extract($args, EXTR_SKIP);
            }
            include $template;
            return;
        }
    }

    /**
     * Action hook when a template part is missing.
     *
     * @param string $slug Template slug.
     * @param string $name Template part name.
     */
    do_action('satori_events_template_missing_part', $slug, $name);

    // Debug output if WP_DEBUG enabled
    if (defined('WP_DEBUG') && WP_DEBUG) {
        echo '<!-- Template part not found: ' . esc_html("{$slug}{$name}") . ' -->';
    }
}
