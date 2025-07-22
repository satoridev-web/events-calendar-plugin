# Events Calendar Plugin for WordPress

A modular, lightweight events calendar plugin built with modern WordPress best practices. Includes a shortcode-based event archive, frontend submission form, responsive design, and clean separation of logic for performance and scalability.

---

## 🔧 Features

- Custom Post Type: `event`
- Shortcode archive layout: `[ec_event_archive]`
- Frontend event submission form
- Grid/List view toggle
- Date, keyword, and taxonomy filters
- Responsive, minimal styling (SCSS compiled)
- Built with namespaced PHP class architecture

---

## 📁 Folder Structure

```
events-calendar-plugin/
├── assets/
│   ├── css/        # Compiled main.css
│   └── scss/       # Source SCSS styles
├── includes/
│   ├── shortcodes/ # Archive shortcode class
│   ├── forms/      # Submission form logic
│   └── functions/  # Core helpers or utilities
├── templates/      # Archive template override
├── events-calendar-plugin.php
└── README.md
```

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

## 📌 Development Notes

- All plugin logic is wrapped in a namespaced class (`Satori_EC\Plugin`) to avoid global conflicts.
- SCSS is compiled into `main.css` with versioned enqueuing for cache busting.
- Future support planned for i18n and settings page.

---

## 📄 License

© 2025 [Satori Graphics Pty Ltd](https://satori.com.au)  
Licensed under the GPLv2 or later.

---

## 🙋 Maintained by

**Andy Garard**  
Director of Training & Design  
[Satori Graphics](https://satori.com.au)
