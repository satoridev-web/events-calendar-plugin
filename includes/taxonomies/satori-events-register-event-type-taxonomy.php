<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Register Custom Taxonomy: Event Type
 * -------------------------------------------------*/
function satori_events_register_event_type_taxonomy(): void
{
    // Taxonomy labels.
    $labels = [
        'name'                  => __('Event Types', 'events-calendar-plugin'),
        'singular_name'         => __('Event Type', 'events-calendar-plugin'),
        'search_items'          => __('Search Event Types', 'events-calendar-plugin'),
        'all_items'             => __('All Event Types', 'events-calendar-plugin'),
        'parent_item'           => __('Parent Event Type', 'events-calendar-plugin'),
        'parent_item_colon'     => __('Parent Event Type:', 'events-calendar-plugin'),
        'edit_item'             => __('Edit Event Type', 'events-calendar-plugin'),
        'update_item'           => __('Update Event Type', 'events-calendar-plugin'),
        'add_new_item'          => __('Add New Event Type', 'events-calendar-plugin'),
        'new_item_name'         => __('New Event Type Name', 'events-calendar-plugin'),
        'menu_name'             => __('Event Types', 'events-calendar-plugin'),
    ];

    // Taxonomy arguments.
    $args = [
        'hierarchical'          => true, // Like categories.
        'labels'                => $labels,
        'show_ui'               => true,  // Required to show in admin menu.
        'show_admin_column'     => true,  // Adds taxonomy column to CPT list table.
        'rewrite'               => ['slug' => 'event-type'],
        'show_in_rest'          => true,  // Enables Gutenberg and REST support.
    ];

    // Register taxonomy for 'event' CPT.
    register_taxonomy('event_type', ['event'], $args);
}

/* -------------------------------------------------
 * Hook taxonomy registration into 'init'
 * -------------------------------------------------*/
add_action('init', 'satori_events_register_event_type_taxonomy');
