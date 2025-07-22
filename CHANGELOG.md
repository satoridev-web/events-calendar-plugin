# Changelog

All notable changes to the Events Calendar Plugin will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/).

---

## [1.0.1-dev] – 2025-07-22

### Added

- Shortcode system now fully modular via `Shortcode_Manager` class, enabling scalable shortcode registration.
- Refactored `[ec_event_archive]` shortcode into its own class: `EC_Archive_Shortcode`.
- Introduced archive template loading system via `ec_locate_template()` for cleaner shortcode rendering.
- Added view toggle support (`grid` vs `list`) with accessible markup and ARIA attributes.
- Improved accessibility: ARIA roles, labels, and `aria-live="polite"` support for dynamic archive rendering.
- Wrapped filters and search inputs in semantic `<fieldset>` blocks with `<legend>` labels.
- Added dedicated template partials for archive filters and view toggles, improving modularity.
- Commenting style now follows SATORI standards throughout all updated plugin files.
- Enhanced i18n readiness using `__()` and `_e()` in all display-facing strings.

### Fixed

- Template escaping/sanitization hardening to improve front-end security and validation.
- Resolved missing CPT visibility in admin by correcting `show_ui` and `has_archive` behavior during rewrite/flush.

### Changed

- Updated `ec-archive-event-shortcode.php` to cleanly separate logic, layout, and presentation layers.
- Reorganized template directory structure to use `/templates/parts/` for partials (filters, toggles, cards, list).
- Template markup updated to match accessibility best practices.

---

## [1.0.0] – 2025-07-22

### Added

- Initial plugin structure with namespace-based architecture
- Custom post type: `event`
- Archive shortcode: `[ec_event_archive]` with grid/list toggle
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
