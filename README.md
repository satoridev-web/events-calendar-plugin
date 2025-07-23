# Events Calendar Plugin for WordPress

A modular, lightweight events calendar plugin built with modern WordPress best practices. Includes a shortcode-based event archive, frontend submission capability, responsive design, and clean separation of logic for performance and scalability.

---

## 🔧 Features

- Custom Post Type: `event`
- Shortcode archive layout: `[ec_event_archive]`
- Grid/List view toggle
- Date, keyword, and taxonomy filters
- Responsive, minimal SCSS-based styling
- Modular file architecture using `ec-*` prefixes
- Built with namespaced PHP class architecture
- Addon-ready design (form builders, taxonomies, metadata, etc.)

---

## 📁 Folder Structure

```plaintext
events-calendar-plugin/
├── admin								# Admin-specific code and settings (if any)
├── assets								# All plugin assets: stylesheets, scripts, and SCSS source
│   ├── css
│   │   └── events-calendar-plugin.css
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
│   │   ├── ec-form-fields.php
│   │   ├── ec-form-handler.php
│   │   └── ec-submission-form.php
│   ├── post-types						# Register custom post types
│   │   └── ec-register-events-cpt.php
│   ├── shortcodes						# Shortcode classes and managers
│   │   ├── class-ec-archive-shortcode.php
│   │   └── class-ec-shortcode-manager.php
│   ├── taxonomies						# Custom taxonomies registrations
│   │   ├── ec-register-event-location-taxonomy.php
│   │   └── ec-register-event-type-taxonomy.php
│   ├── ec-excerpt-override.php			# Custom excerpt helper functions
│   ├── ec-meta-boxes.php				# Meta boxes for event CPT
│   ├── ec-search-filter.php			# Search and filter logic
│   ├── ec-session.php					# Session handling helpers
│   ├── ec-template-functions.php		# Template helper functions
│   └── ec-template-loader.php			# Template loading overrides
├── languages							# Translation files (.pot, .mo, .po)
├── templates							# Frontend template files and partials
│   ├── parts							# Template parts (filters, view toggles, event cards)
│   │   ├── ec-archive-filters.php
│   │   ├── ec-archive-view-toggle.php
│   │   ├── ec-content-event-card.php
│   │   └── ec-content-event-list.php
│   ├── ec-archive-event-shortcode.php	# Shortcode archive template
│   ├── ec-archive-event.php			# Default archive template
│   └── ec-single-event.php				# Single event template
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
   ```
2. Activate the plugin in your WordPress admin.
3. Add `[ec_event_archive]` to any page to render the archive view.

---

## 🧩 Shortcodes

### `[ec_event_archive]`

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
- File naming is consistently prefixed with `ec-` for clarity and maintainability.
- SCSS is modular and compiled into `ec-style.css` with versioned cache busting.
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
