# Events Calendar Plugin for WordPress

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

````plaintext
events-calendar-plugin/
├── admin								# Admin-specific code and settings (if any)
├── assets								# All plugin assets: stylesheets, scripts, and SCSS source
│   ├── css
│   │   └── satori-style.css
│   ├── js
│   │   └── filter-toggle.js
│   └── scss							# Sass partials and main entry
│       ├── _animations.scss
│       ├── _archive.scss
│       ├── _base.scss
│       ├── _event-single.scss
│       ├── _form.scss
│       ├── _mixins.scss
│       ├── _variables.scss
│       └── main.scss
├── includes							# Core PHP includes: forms, CPTs, taxonomies, shortcodes, helpers
│   ├── forms							# Frontend form handlers and fields
│   │   ├── satori-form-fields.php
│   │   ├── satori-form-handler.php
│   │   └── satori-submission-form.php
│   ├── post-types						# Register custom post types
│   │   └── satori-register-events-cpt.php
│   ├── shortcodes						# Shortcode classes and managers
│   │   ├── class-satori-archive-shortcode.php
│   │   └── class-satori-shortcode-manager.php
│   ├── taxonomies						# Custom taxonomies registrations
│   │   ├── satori-register-event-location-taxonomy.php
│   │   └── satori-register-event-type-taxonomy.php
│   ├── satori-excerpt-override.php			# Custom excerpt helper functions
│   ├── satori-meta-boxes.php				# Meta boxes for event CPT
│   ├── satori-search-filter.php			# Search and filter logic
│   ├── satori-session.php					# Session handling helpers
│   ├── satori-template-functions.php		# Template helper functions
│   └── satori-template-loader.php			# Template loading overrides
├── languages							# Translation files (.pot, .mo, .po)
├── templates							# Frontend template files and partials
│   ├── parts							# Template parts (filters, view toggles, event cards)
│   │   ├── satori-archive-filters.php
│   │   ├── satori-archive-view-toggle.php
│   │   ├── satori-content-event-card.php
│   │   └── satori-content-event-list.php
│   ├── satori-archive-event-shortcode.php	# Shortcode archive template
│   ├── satori-archive-event.php			# Default archive template
│   └── satori-single-event.php				# Single event template
├── CHANGELOG.md						# Plugin changelog file
├── LICENSE								# Plugin license
├── README.md							# Plugin README file
├── events-calendar-plugin.php			# Main plugin bootstrap file
└── folder-structure.txt				# Text file of plugin folder structure

---

## 🚀 Installation

1. Download or clone the plugin into your `wp-content/plugins/` directory:
   ```bash
   git clone https://github.com/satoridev-web/events-calendar-plugin.git
````

2. Activate the plugin in your WordPress admin.
3. Add `[satori_events_event_archive]` to any page to render the archive view.

---

## 🧩 Shortcodes

### `[satori_events_event_archive]`

Displays a filterable, toggleable archive of published events.

Supports:

- Grid/List views
- Taxonomy and keyword filters
- Clean HTML structure for custom theming

---

## 🧪 Requirements

- WordPress 6.0+
- PHP 7.4+
- SCSS compiler (optional, for custom styles)

---

## 🧱 Modular Addons

The plugin is designed to be extended via optional addons that can work independently or alongside the core plugin.

Addon examples include:

- Form builder integrations
- Advanced taxonomy systems
- Additional metadata fields
- Custom filtering controls

---

## 📌 Development Notes

- All plugin logic is namespaced under `Satori_EC\` to avoid global conflicts.
- File naming is consistently prefixed with `satori-` for clarity and maintainability.
- SCSS is modular and compiled into `satori-style.css` with versioned cache busting.
- Addon files should follow the same naming and folder structure conventions.
- Future support planned for internationalization and admin settings.

---

## 📄 License

© 2025 [Satori Graphics Pty Ltd](https://satori.com.au)  
Licensed under the GPLv2 or later.

---

## 🙋 Maintained by

**Andy Garard**  
Director of Training & Design  
[Satori Graphics](https://satori.com.au)
