/**
 * SATORI Events – AJAX Tools Admin Script
 */
jQuery(document).ready(function ($) {

    // Utility to show admin notice
    const showNotice = (message, type = 'success') => {
        const $notice = $(`
            <div class="notice notice-${type} is-dismissible">
                <p>${message}</p>
            </div>
        `);
        $('.wrap h1').after($notice);
    };

    // Metadata Refresh
    $('#satori-events-refresh-meta').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true).text('Running…');

        $.post(satoriEvents.ajax_url, {
            action: 'satori_events_refresh_metadata',
            nonce: satoriEvents.ajax_nonce,
        }).done(function (response) {
            showNotice(response.data.message || 'Metadata refreshed.');
        }).fail(function () {
            showNotice('Error refreshing metadata.', 'error');
        }).always(function () {
            $btn.prop('disabled', false).text('Run');
        });
    });

    // Cache Clear
    $('#satori-events-clear-cache').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true).text('Running…');

        $.post(satoriEvents.ajax_url, {
            action: 'satori_events_clear_cache',
            nonce: satoriEvents.ajax_nonce,
        }).done(function (response) {
            showNotice(response.data.message || 'Cache cleared.');
        }).fail(function () {
            showNotice('Error clearing cache.', 'error');
        }).always(function () {
            $btn.prop('disabled', false).text('Run');
        });
    });
});
