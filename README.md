# SATORI Events Plugin for WordPress

A modular, lightweight events calendar plugin built with modern WordPress best practices. Includes a shortcode-based event archive, frontend submission capability, responsive design, and clean separation of logic for performance and scalability.

---

## 🔧 Features

- Custom Post Type: `event`
- Shortcode archive layout: `[satori_events_event_archive]`
- Grid/List view toggle
- Date, keyword, and taxonomy filters
- Responsive, minimal SCSS-based styling
- Modular file architecture using `satori-*` prefixes
- Built with namespaced PHP class architecture
- Addon-ready design (form builders, taxonomies, metadata, etc.)

---

## 📁 Folder Structure

```plaintext
events-calendar-plugin/
├── admin                                                      # Admin-specific logic and pages (e.g., tools page)
│   └── class-satori-events-tools-page.php                     # Renders the Tools admin submenu with AJAX buttons
├── assets                                                     # All front-end and admin assets (CSS/JS/SCSS)
│   ├── css
│   │   ├── satori-events-main-legacy.css                      # Legacy compiled CSS (deprecated but retained)
│   │   ├── satori-events-main.css                             # Main compiled CSS from SCSS
│   │   └── satori-events-main.css.map                         # Source map for debugging compiled styles
│   ├── js
│   │   ├── satori-events-ajax.js                              # Handles AJAX requests for admin tools
│   │   ├── satori-events-filter-toggle.js                     # JS for toggling archive filters on the front-end
│   │   └── satori-events-tools.js                             # (Optional) Additional interactivity for tools page
│   └── scss                                                   # Modular SCSS source files (compiled to CSS)
│       ├── _animations.scss                                   # Key animations for UI interactions
│       ├── _archive.scss                                      # Styles specific to archive grid/list layout
│       ├── _base.scss                                         # Base typography and resets
│       ├── _event-single.scss                                 # Styles for single event view
│       ├── _form.scss                                         # Form layout and input styles
│       ├── _mixins.scss                                       # SCSS mixins for reuse
│       ├── _variables.scss                                    # SCSS variables (colors, breakpoints, etc.)
│       └── satori-events-main.scss                            # Main SCSS entry point
├── includes                                                   # Core PHP logic and plugin functionality
│   ├── ajax
│   │   └── class-satori-events-ajax-handler.php # Central AJAX router for backend admin tools
│   ├── forms
│   │   ├── satori-events-form-fields.php                      # Frontend form field rendering
│   │   ├── satori-events-form-handler.php                     # Submission handler and validation
│   │   └── satori-events-submission-form.php                  # Shortcode template for frontend submission
│   ├── post-types
│   │   └── satori-events-register-events-cpt.php # Registers the 'event' custom post type
│   ├── shortcodes
│   │   ├── class-satori-events-archive-shortcode.php          # Renders event archive via shortcode
│   │   └── class-satori-events-shortcode-manager.php          # Central manager for all plugin shortcodes
│   ├── taxonomies
│   │   ├── satori-events-register-event-location-taxonomy.php # 'Location' taxonomy
│   │   └── satori-events-register-event-type-taxonomy.php     # 'Event Type' taxonomy
│   ├── satori-events-excerpt-override.php                     # Custom excerpt trimming and control
│   ├── satori-events-meta-boxes.php                           # Meta boxes for event-specific data
│   ├── satori-events-search-filter.php                        # Backend and frontend search/filter helpers
│   ├── satori-events-session.php                              # Session utilities for persistent filters
│   ├── satori-events-template-functions.php                   # General template helper functions
│   └── satori-events-template-loader.php                      # Overrides default template hierarchy
├── languages                                                  # Localization files for i18n support
├── templates                                                  # Frontend display templates for events
│   ├── parts
│   │   ├── satori-events-archive-filters.php                  # Renders archive filter UI (taxonomies/search)
│   │   ├── satori-events-archive-view-toggle.php              # Grid/List toggle buttons
│   │   ├── satori-events-content-event-card.php               # Template for grid view cards
│   │   └── satori-events-content-event-list.php               # Template for list view entries
│   ├── satori-events-archive-event-shortcode.php              # Archive layout when using shortcode
│   ├── satori-events-archive-event.php                        # Default archive template override
│   └── satori-events-single-event.php                         # Single event display template
├── CHANGELOG.md                                               # Version history and notable updates
├── LICENSE                                                    # GPLv2+ license declaration
├── README.md                                                  # This readme file
├── SATORI-PLUGIN-NAMING-GUIDE.md                              # Developer guide for naming and structuring files
├── satori-events-folder-structure.txt                         # Plain text version of this file structure
└── satori-events.php                                          # Main plugin bootstrap file

🚀 Installation

    Download or clone the plugin into your wp-content/plugins/ directory:

    git clone https://github.com/satoridev-web/events-calendar-plugin.git

    Activate the plugin from the Plugins screen in WordPress.

    Add [satori_events_event_archive] to any page or post to render the archive view.

🧩 Shortcodes
[satori_events_event_archive]

Renders a full archive view of your events with optional filters and view toggles.

Includes:

    Grid or List layout toggle

    Date and keyword filtering

    Custom taxonomy dropdown filters (event_location, event_type)

    Fully responsive output with customizable templates

🧪 Requirements

    WordPress 6.0 or higher

    PHP 7.4 or higher

    Optional: SCSS compiler for customizing or extending plugin styles

🧱 Modular Addons

The SATORI Events plugin is designed with modularity in mind.

You can build or integrate optional addons, such as:

    Custom form builders (e.g., Formidable, ACF Frontend)

    Extended taxonomy and filtering systems

    Additional metadata and sorting

    Role-based access or submission moderation

🔒 Admin Tools

The plugin includes a secure admin-only Tools panel accessible to users with the satori_events_tools capability.

Available actions (AJAX-based):

    Refresh Metadata: Rebuilds event post metadata (e.g., ACF fields)

    Clear Cache: Removes transient data (if implemented)

    Toggle Debug Mode: Enables debug logging or verbose output

All actions are protected with WordPress nonces.
📌 Development Notes

    All code is namespaced under Satori_EC\ to avoid collisions with themes or other plugins.

    File names consistently use the satori-events-* prefix.

    Frontend styles are modular and compiled from SCSS into a single minified CSS file.

    Templates follow the WordPress theme hierarchy, with overrides allowed via theme folders.

    Admin Tools are fully decoupled and extendable via hooks and AJAX.

📄 License

© 2025 Satori Graphics Pty Ltd
Licensed under the GPLv2 or later.
🙋 Maintained by

Andy Garard
Director of Training & Design
Satori Graphics
```
