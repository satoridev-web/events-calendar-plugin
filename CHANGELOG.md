# Changelog

All notable changes to the Events Calendar Plugin will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/).

---

## [1.0.1] – 2025-07-22

### Added

- Improved accessibility: Added ARIA roles, labels, and `aria-live="polite"` support to event archive templates.
- Wrapped filter inputs in `<fieldset>` with `<legend>` for better semantic grouping and accessibility.
- Enhanced internationalization (i18n) support throughout shortcode and template files using `__()` and `_e()`.
- Added a view toggle UI with proper ARIA attributes and keyboard accessible state management.
- SATORI commenting standard applied consistently across templates and plugin files.

### Fixed

- Fixed escaping and sanitization issues in templates to improve security and stability.
- Resolved Git commit blocking issue by configuring global user name and email.

### Changed

- Updated README.md with latest plugin features, instructions, and best practices.

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
