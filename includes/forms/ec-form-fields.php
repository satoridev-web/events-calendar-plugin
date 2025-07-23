<?php

/**
 * Define Event Submission Fields
 *
 * Returns an array of form fields used in the frontend event submission form.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit;

/**
 * Get the default event submission fields.
 *
 * Each field supports:
 * - label (string)
 * - type (text, textarea, date, time, taxonomy)
 * - required (bool)
 * - taxonomy (if type = taxonomy)
 *
 * @return array<string, array<string, mixed>>
 */
function ec_get_event_submission_fields(): array
{
    // ------------------------------------------------------------------------------
    // DEFINE: Default field structure for frontend event submission form
    // ------------------------------------------------------------------------------

    $fields = [
        'event_title' => [
            'label'    => __('Event Title', 'events-calendar-plugin'),
            'type'     => 'text',
            'required' => true,
        ],
        'event_description' => [
            'label'    => __('Event Description', 'events-calendar-plugin'),
            'type'     => 'textarea',
            'required' => true,
        ],
        'event_date' => [
            'label'    => __('Event Date', 'events-calendar-plugin'),
            'type'     => 'date',
            'required' => true,
        ],
        'event_time' => [
            'label'    => __('Event Time', 'events-calendar-plugin'),
            'type'     => 'time',
            'required' => true,
        ],
        'event_location' => [
            'label'    => __('Event Location', 'events-calendar-plugin'),
            'type'     => 'text',
            'required' => true,
        ],
        'event_type' => [
            'label'    => __('Event Type', 'events-calendar-plugin'),
            'type'     => 'taxonomy',
            'taxonomy' => 'event_type',
            'required' => true,
        ],
    ];

    // ------------------------------------------------------------------------------
    // FILTER: Allow other plugins or extensions to modify submission fields
    // ------------------------------------------------------------------------------

    return apply_filters('ec_event_submission_fields', $fields);
}
