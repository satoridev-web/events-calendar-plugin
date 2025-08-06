<?php

namespace Satori_Events\Admin;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * SATORI Events Tools Admin Page
 * -------------------------------------------------*/

class Tools_Page
{

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_tools_submenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Keep fallback handlers for POST forms
        add_action('admin_post_satori_events_refresh_metadata', [$this, 'handle_refresh_metadata']);
        add_action('admin_post_satori_events_clear_cache', [$this, 'handle_clear_cache']);
        add_action('admin_post_satori_events_toggle_debug', [$this, 'handle_toggle_debug']);
    }

    /* -------------------------------------------------
     * Register Tools Submenu
     * -------------------------------------------------*/
    public function register_tools_submenu(): void
    {
        add_submenu_page(
            'edit.php?post_type=event',
            __('Tools', 'satori-events'),
            __('Tools', 'satori-events'),
            'manage_options',
            'satori-events-tools',
            [$this, 'render_tools_page']
        );
    }

    /* -------------------------------------------------
     * Enqueue admin scripts & localize data
     * -------------------------------------------------*/
    public function enqueue_scripts(): void
    {
        $screen = get_current_screen();
        if ($screen && $screen->id === 'event_page_satori_events-tools') {
            wp_enqueue_script(
                'satori-events-ajax',
                SATORI_EVENTS_PLUGIN_URL . 'assets/js/satori-events-ajax.js',
                ['jquery'],
                SATORI_EVENTS_VERSION,
                true
            );

            wp_localize_script('satori-events-ajax', 'satoriEvents', [
                'ajax_url'   => admin_url('admin-ajax.php'),
                'ajax_nonce' => wp_create_nonce('satori_events_tools_nonce'),
                'strings'    => [
                    'error' => __('Something went wrong. Please try again.', 'satori-events'),
                ],
            ]);
        }
    }

    /* -------------------------------------------------
     * Render Tools Page
     * -------------------------------------------------*/
    public function render_tools_page(): void
    {
        $debug_enabled = get_option('satori_events_debug_mode') === '1';
?>
        <div class="wrap">
            <h1><?php esc_html_e('SATORI Events Tools', 'satori-events'); ?></h1>

            <?php if (isset($_GET['satori_action'])) : ?>
                <div class="notice notice-success is-dismissible">
                    <?php
                    switch ($_GET['satori_action']) {
                        case 'refreshed':
                            echo '<p>' . esc_html__('Event metadata refreshed successfully.', 'satori-events') . '</p>';
                            break;
                        case 'cache_cleared':
                            echo '<p>' . esc_html__('Event cache cleared successfully.', 'satori-events') . '</p>';
                            break;
                        case 'debug_enabled':
                            echo '<p>' . esc_html__('Debug mode enabled.', 'satori-events') . '</p>';
                            break;
                        case 'debug_disabled':
                            echo '<p>' . esc_html__('Debug mode disabled.', 'satori-events') . '</p>';
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Tool', 'satori-events'); ?></th>
                        <th><?php esc_html_e('Description', 'satori-events'); ?></th>
                        <th><?php esc_html_e('Action', 'satori-events'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php esc_html_e('Refresh Metadata', 'satori-events'); ?></td>
                        <td><?php esc_html_e('Re-save metadata for all published events (e.g. after code or field changes).', 'satori-events'); ?></td>
                        <td>
                            <button id="satori-events-refresh-meta" data-satori-action="satori_events_refresh_metadata" class="button button-secondary">
                                <?php esc_html_e('Run', 'satori-events'); ?>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Clear Cache', 'satori-events'); ?></td>
                        <td><?php esc_html_e('Flush internal object cache and transient data used by the plugin.', 'satori-events'); ?></td>
                        <td>
                            <button id="satori-events-clear-cache" data-satori-action="satori_events_clear_cache" class="button button-secondary">
                                <?php esc_html_e('Run', 'satori-events'); ?>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Debug Mode', 'satori-events'); ?></td>
                        <td><?php esc_html_e('Toggle verbose debug output (e.g. template loaded, query vars).', 'satori-events'); ?></td>
                        <td>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <?php wp_nonce_field('satori_events_toggle_debug'); ?>
                                <input type="hidden" name="action" value="satori_events_toggle_debug">
                                <?php
                                $label = $debug_enabled ? __('Disable Debug', 'satori-events') : __('Enable Debug', 'satori-events');
                                $class = $debug_enabled ? 'button-secondary' : 'button-primary';
                                submit_button($label, $class, 'submit', false);
                                ?>
                                <span style="margin-left: 10px;"><strong>Status:</strong> <?php echo $debug_enabled ? '<span style="color: green;">ON</span>' : '<span style="color: red;">OFF</span>'; ?></span>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
<?php
    }

    /* -------------------------------------------------
     * Handle Metadata Refresh (Fallback if forms used)
     * -------------------------------------------------*/
    public function handle_refresh_metadata(): void
    {
        if (!current_user_can('manage_options') || !check_admin_referer('satori_events_refresh_metadata')) {
            wp_die(__('Permission denied or invalid nonce.', 'satori-events'));
        }

        $this->refresh_metadata();

        wp_redirect(admin_url('edit.php?post_type=event&page=satori-events-tools&satori_action=refreshed'));
        exit;
    }

    /* -------------------------------------------------
     * Handle Cache Clear (Fallback if forms used)
     * -------------------------------------------------*/
    public function handle_clear_cache(): void
    {
        if (!current_user_can('manage_options') || !check_admin_referer('satori_events_clear_cache')) {
            wp_die(__('Permission denied or invalid nonce.', 'satori-events'));
        }

        wp_cache_flush();

        wp_redirect(admin_url('edit.php?post_type=event&page=satori-events-tools&satori_action=cache_cleared'));
        exit;
    }

    /* -------------------------------------------------
     * Handle Debug Mode Toggle (always non-AJAX)
     * -------------------------------------------------*/
    public function handle_toggle_debug(): void
    {
        if (!current_user_can('manage_options') || !check_admin_referer('satori_events_toggle_debug')) {
            wp_die(__('Permission denied or invalid nonce.', 'satori-events'));
        }

        $debug_enabled = get_option('satori_events_debug_mode') === '1';
        update_option('satori_events_debug_mode', $debug_enabled ? '0' : '1');

        $redirect_action = $debug_enabled ? 'debug_disabled' : 'debug_enabled';
        wp_redirect(admin_url('edit.php?post_type=event&page=satori-events-tools&satori_action=' . $redirect_action));
        exit;
    }

    /* -------------------------------------------------
     * Utility: Refresh Event Metadata
     * -------------------------------------------------*/
    private function refresh_metadata(): void
    {
        $args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];

        $events = get_posts($args);

        foreach ($events as $event) {
            $event_id = $event->ID;

            $date     = get_post_meta($event_id, '_satori_events_date', true);
            $time     = get_post_meta($event_id, '_satori_events_time', true);
            $location = get_post_meta($event_id, '_satori_events_location', true);

            if ($date) {
                update_post_meta($event_id, '_satori_events_date', $date);
            }

            if ($time) {
                update_post_meta($event_id, '_satori_events_time', $time);
            }

            if ($location) {
                update_post_meta($event_id, '_satori_events_location', $location);
            }
        }
    }
}

// --------------------------------------------------
// Initialise Tools Page
// --------------------------------------------------
new Tools_Page();
