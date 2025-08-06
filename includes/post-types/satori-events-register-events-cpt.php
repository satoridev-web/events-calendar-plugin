<?php

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Register Custom Post Type: Event
 * -------------------------------------------------*/
function satori_events_register_event_post_type(): void
{
    $labels = [
        'name'                  => __('Events', 'events-calendar-plugin'),
        'singular_name'         => __('Event', 'events-calendar-plugin'),
        'menu_name'             => __('Events', 'events-calendar-plugin'),
        'name_admin_bar'        => __('Event', 'events-calendar-plugin'),
        'add_new'               => __('Add New', 'events-calendar-plugin'),
        'add_new_item'          => __('Add New Event', 'events-calendar-plugin'),
        'edit_item'             => __('Edit Event', 'events-calendar-plugin'),
        'new_item'              => __('New Event', 'events-calendar-plugin'),
        'view_item'             => __('View Event', 'events-calendar-plugin'),
        'all_items'             => __('All Events', 'events-calendar-plugin'),
        'search_items'          => __('Search Events', 'events-calendar-plugin'),
        'not_found'             => __('No events found.', 'events-calendar-plugin'),
        'not_found_in_trash'    => __('No events found in Trash.', 'events-calendar-plugin'),
    ];

    $args = [
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => ['slug' => 'events'],
        'menu_icon'             => 'dashicons-calendar-alt',
        'supports'              => ['title', 'editor', 'thumbnail'],
        'show_in_rest'          => true, // Enables Gutenberg + REST API support.
    ];

    register_post_type('event', $args);
}

/* -------------------------------------------------
 * Hook CPT registration to 'init'
 * -------------------------------------------------*/
add_action('init', 'satori_events_register_event_post_type');
