<?php

namespace Satori_Events;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Event Submission Form Fields Definition
 * -------------------------------------------------*/

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
function satori_events_get_event_submission_fields(): array
{
    // -------------------------------------------------
    // Define default fields for frontend event submission form
    // -------------------------------------------------
    $fields = [
        'event_title' => [
            'label'    => __('Event Title', 'satori-events'),
            'type'     => 'text',
            'required' => true,
        ],
        'event_description' => [
            'label'    => __('Event Description', 'satori-events'),
            'type'     => 'textarea',
            'required' => true,
        ],
        'event_date' => [
            'label'    => __('Event Date', 'satori-events'),
            'type'     => 'date',
            'required' => true,
        ],
        'event_time' => [
            'label'    => __('Event Time', 'satori-events'),
            'type'     => 'time',
            'required' => true,
        ],
        'event_location' => [
            'label'    => __('Event Location', 'satori-events'),
            'type'     => 'text',
            'required' => true,
        ],
        'event_type' => [
            'label'    => __('Event Type', 'satori-events'),
            'type'     => 'taxonomy',
            'taxonomy' => 'event_type',
            'required' => true,
        ],
    ];

    // -------------------------------------------------
    // Filter: Allow other plugins or extensions to modify submission fields
    // -------------------------------------------------
    return apply_filters('satori_events_event_submission_fields', $fields);
}
