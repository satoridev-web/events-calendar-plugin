<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Register Custom Taxonomy: Event Location
 * -------------------------------------------------*/
function satori_events_register_event_location_taxonomy(): void
{
    $labels = [
        'name'              => __('Event Locations', 'events-calendar-plugin'),
        'singular_name'     => __('Event Location', 'events-calendar-plugin'),
        'search_items'      => __('Search Event Locations', 'events-calendar-plugin'),
        'all_items'         => __('All Event Locations', 'events-calendar-plugin'),
        'parent_item'       => __('Parent Location', 'events-calendar-plugin'),
        'parent_item_colon' => __('Parent Location:', 'events-calendar-plugin'),
        'edit_item'         => __('Edit Location', 'events-calendar-plugin'),
        'update_item'       => __('Update Location', 'events-calendar-plugin'),
        'add_new_item'      => __('Add New Location', 'events-calendar-plugin'),
        'new_item_name'     => __('New Location Name', 'events-calendar-plugin'),
        'menu_name'         => __('Event Locations', 'events-calendar-plugin'),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'event-location'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('event_location', ['event'], $args);
}

/* -------------------------------------------------
 * Hook taxonomy registration into 'init'
 * -------------------------------------------------*/
add_action('init', 'satori_events_register_event_location_taxonomy');
