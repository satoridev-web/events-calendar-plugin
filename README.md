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
├── admin/                            	# Admin-related functionality (e.g., settings, columns)
├── assets/                          	# Frontend assets
│   ├── css/
│   │   └── events-calendar-plugin.css  # Compiled main stylesheet
│   ├── js/
│   │   └── filter-toggle.js         	# JS for filter and view toggles
│   └── scss/                        	# Source SCSS partials and main stylesheet
│       ├── _animations.scss
│       ├── _archive.scss
│       ├── _base.scss
│       ├── _event-single.scss
│       ├── _form.scss
│       ├── _mixins.scss
│       ├── _variables.scss
│       └── main.scss
├── includes/                        	# Core PHP includes
│   ├── forms/                      	# Frontend form handling
│   │   ├── ec-form-fields.php       	# Form fields definitions
│   │   ├── ec-form-handler.php      	# Form processing logic
│   │   └── ec-submission-form.php   	# Frontend submission form
│   ├── post-types/                  	# Custom post types registration
│   │   └── ec-register-events-cpt.php
│   ├── shortcodes/                  	# Shortcode classes
│   │   ├── class-ec-archive-shortcode.php
│   │   └── class-ec-shortcode-manager.php
│   ├── taxonomies/                  	# Custom taxonomy registration
│   │   └── ec-register-event-categories.php
│   ├── ec-excerpt-override.php      	# Excerpt customizations
│   ├── ec-meta-boxes.php            	# Meta box registrations
│   ├── ec-search-filter.php         	# Search and filter logic
│   ├── ec-session.php               	# Session handling
│   └── ec-template-loader.php       	# Template loading helpers
├── languages/                      	# Localization files (PO/MO)
├── templates/                      	# Frontend templates
│   ├── parts/                      	# Template partials
│   │   ├── ec-archive-filters.php
│   │   ├── ec-archive-view-toggle.php
│   │   ├── ec-content-event-card.php
│   │   └── ec-content-event-list.php
│   ├── ec-archive-event-shortcode.php
│   ├── ec-archive-event.php
│   └── ec-single-event.php
├── CHANGELOG.md                    	# Plugin change log
├── LICENSE                        		# License file
├── README.md                      		# Plugin readme
└── events-calendar-plugin.php     		# Main plugin bootstrap file
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
