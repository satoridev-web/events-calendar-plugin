<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Register Custom Taxonomy: Event Type
 */
function ec_register_event_taxonomy() {
    $labels = array(
        'name'              => __('Event Types', 'events-calendar-plugin'),
        'singular_name'     => __('Event Type', 'events-calendar-plugin'),
        'search_items'      => __('Search Event Types', 'events-calendar-plugin'),
        'all_items'         => __('All Event Types', 'events-calendar-plugin'),
        'parent_item'       => __('Parent Event Type', 'events-calendar-plugin'),
        'parent_item_colon' => __('Parent Event Type:', 'events-calendar-plugin'),
        'edit_item'         => __('Edit Event Type', 'events-calendar-plugin'),
        'update_item'       => __('Update Event Type', 'events-calendar-plugin'),
        'add_new_item'      => __('Add New Event Type', 'events-calendar-plugin'),
        'new_item_name'     => __('New Event Type Name', 'events-calendar-plugin'),
        'menu_name'         => __('Event Types', 'events-calendar-plugin'),
    );

    $args = array(
        'hierarchical'      => true, // true = like categories, false = like tags
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'event-type'),
        'show_in_rest'      => true, // Enable for block editor + REST
    );

    register_taxonomy('event_type', array('event'), $args);
}
add_action('init', 'ec_register_event_taxonomy');