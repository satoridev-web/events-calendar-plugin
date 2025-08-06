# Changelog

All notable changes to the Events Calendar Plugin will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/).

---

## [1.1.0] - 2025-08-06

### Added

- New **SATORI Events Tools** submenu under the WordPress admin, featuring:
  - **Refresh Metadata**: Regenerates metadata for events.
  - **Clear Cache**: Clears transient cache (if applicable).
  - **Debug Mode Toggle**: Enables or disables plugin-specific logging/debug features.
- AJAX-based interactivity for all admin tools.
- Capability check: Tools menu visibility restricted to users with the custom `satori_events_tools` capability.
- Nonce protection added for all AJAX requests to ensure security.

### Files Introduced

- `admin/class-satori-events-tools-page.php`: Renders the Tools admin page, handles form and button UI, and enqueues JS.
- `includes/ajax/class-satori-events-ajax-handler.php`: Centralized AJAX router for handling backend tools logic.
- `assets/js/satori-events-ajax.js`: JavaScript file responsible for triggering AJAX requests from the Tools UI.

### Changed

- `satori-events.php`: Registered the new Tools page, enqueued scripts, and initialized AJAX handler.

### Notes

- This update lays the foundation for future admin utilities.
- All actions are designed to be extensible via hooks and filters.

---

## [1.0.2-dev] – 2025-07-30

### Added

- Integrated modular Sass (.scss) architecture for styles:
  - Variables, mixins, animations, base, form, single event, and archive styles.
  - Master SCSS file compiling to `satori-events.css`.
- Updated naming conventions to align with SATORI branding (`satori-events` prefix).
- Separated 'Event Location' (text input for venue/address) from 'Location' taxonomy (geographical region/state/shire).
- Enhanced frontend event submission fields for clarity and extensibility.
- Prepped plugin codebase for full rename/refactor with SATORI prefixing.

### Changed

- Refactored SCSS files to follow SATORI standardized commenting style.
- Adjusted folder and file structure to better support future SATORI plugin family expansion.
- Updated all references to 'location' to clarify taxonomy vs. venue/address usage.

---

## [1.0.1-dev] – 2025-07-23

### Added

- Shortcode system now fully modular via `Shortcode_Manager` class, enabling scalable shortcode registration.
- Refactored `[satori_events_event_archive]` shortcode into its own class: `satori_events_Archive_Shortcode`.
- Introduced archive template loading system via `satori_events_locate_template()` for cleaner shortcode rendering.
- Added view toggle support (`grid` vs `list`) with accessible markup and ARIA attributes.
- Improved accessibility: ARIA roles, labels, and `aria-live="polite"` support for dynamic archive rendering.
- Wrapped filters and search inputs in semantic `<fieldset>` blocks with `<legend>` labels.
- Added dedicated template partials for archive filters and view toggles, improving modularity.
- Commenting style now follows SATORI standards throughout all updated plugin files.
- Enhanced i18n readiness using `__()` and `_e()` in all display-facing strings.
- Updated core plugin bootstrap file to include taxonomy registration and all includes with correct `satori-*` naming and folder structure.
- Improved event archive filtering query and asset enqueuing consistency.
- Added taxonomy registration (`event_type`) include and hooked correctly.
- Enforced plugin text domain loading with updated domain `satori-events`.

### Fixed

- Template escaping/sanitization hardening to improve front-end security and validation.
- Resolved missing CPT visibility in admin by correcting `show_ui` and `has_archive` behavior during rewrite/flush.
- Fixed missing taxonomy admin menu by ensuring taxonomy registration file is included properly.
- Corrected enqueue paths for CSS/JS assets to match actual plugin structure.

### Changed

- Updated `satori-archive-event-shortcode.php` to cleanly separate logic, layout, and presentation layers.
- Reorganized template directory structure to use `/templates/parts/` for partials (filters, toggles, cards, list).
- Template markup updated to match accessibility best practices.
- Refactored core plugin class to properly load all modular files from `includes/` subfolders.
- Standardized all file includes to follow SATORI naming conventions (`satori-*` prefix).
- Adopted improved folder/file organization reflecting current GitHub repo structure.

---

## [1.0.0] – 2025-07-23

### Added

- Registered custom post type 'event' with taxonomies for categories, locations, and event types.
- Frontend event archive with filters, search, and view toggles.
- Custom excerpt helper function for event excerpts.
- Shortcodes for event archive display.

### Fixed

- Corrected taxonomy include paths in core plugin file.
- Fixed archive query to return correct number of events.
- Added defensive programming to taxonomy filters to prevent PHP warnings.

### Changed

- Renamed 'region' taxonomy to 'location' for clarity.
- Moved excerpt helper loading to plugin bootstrap for proper availability.

---

## [1.0.0] – 2025-07-22

### Added

- Initial plugin structure with namespace-based architecture
- Custom post type: `event`
- Archive shortcode: `[satori_events_event_archive]` with grid/list toggle
- Frontend event submission form scaffold
- Responsive SCSS-based styling system
- Modular includes directory for shortcodes, forms, and utilities
- Asset versioning and enqueue system

---

## [Unreleased]

### Planned

- i18n support for full plugin translation
- Admin settings page
- Calendar (month) view
- Recurring events support
- Google Calendar export (.ics)
