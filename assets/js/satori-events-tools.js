jQuery(document).ready(function ($) {
    // Helper to display admin notices in the tools page
    function showNotice(message, type = 'success') {
        const noticeDiv = $('#satori-events-admin-notices');
        const color = type === 'success' ? '#46b450' : '#dc3232';
        noticeDiv
            .html('<div style="color: ' + color + '; font-weight: bold; margin-bottom: 1em;">' + message + '</div>')
            .fadeIn();

        setTimeout(() => noticeDiv.fadeOut(), 5000);
    }

    // Refresh Metadata Button Click
    $('#satori-refresh-meta').on('click', function () {
        const button = $(this);
        button.prop('disabled', true).text('Running…');

        $.post(SatoriEventsTools.ajax_url, {
            action: 'satori_refresh_meta',
            nonce: SatoriEventsTools.nonce,
        })
            .done(function (response) {
                if (response.success) {
                    showNotice(SatoriEventsTools.i18n.success + ' ' + response.data);
                } else {
                    showNotice(SatoriEventsTools.i18n.error + ' ' + response.data, 'error');
                }
            })
            .fail(function () {
                showNotice(SatoriEventsTools.i18n.error, 'error');
            })
            .always(function () {
                button.prop('disabled', false).text('Run Now');
            });
    });

    // Clear Cache Button Click
    $('#satori-clear-cache').on('click', function () {
        const button = $(this);
        button.prop('disabled', true).text('Clearing…');

        $.post(SatoriEventsTools.ajax_url, {
            action: 'satori_clear_cache',
            nonce: SatoriEventsTools.nonce,
        })
            .done(function (response) {
                if (response.success) {
                    showNotice(SatoriEventsTools.i18n.success + ' ' + response.data);
                } else {
                    showNotice(SatoriEventsTools.i18n.error + ' ' + response.data, 'error');
                }
            })
            .fail(function () {
                showNotice(SatoriEventsTools.i18n.error, 'error');
            })
            .always(function () {
                button.prop('disabled', false).text('Clear Cache');
            });
    });
});
