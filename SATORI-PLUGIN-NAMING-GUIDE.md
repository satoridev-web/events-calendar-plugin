# SATORI Plugin Naming Convention Guide

## 📦 Overview

To maintain consistency and clarity across the SATORI suite of plugins — including **SATORI Events**, **SATORI Forms**, and **SATORI Tickets** — we adopt the following naming conventions and prefix strategy.

---

## 🔖 Brand Identity Prefix

| Plugin         | Prefix           |
| -------------- | ---------------- |
| SATORI Events  | `satori-events`  |
| SATORI Forms   | `satori-forms`   |
| SATORI Tickets | `satori-tickets` |

### SCSS/CSS Naming

Use dashes in SCSS/CSS filenames:

- `satori-events.scss`
- `satori-events-main.css` (compiled output)

### PHP Prefixes (classes, functions, constants)

Use underscores for internal code elements:

- `class_satori_events_shortcode`
- `satori_events_register_post_type()`
- `SATORI_EVENTS_VERSION`

---

## 🎨 SCSS Structure

SASS files are modularized and imported into a single `main.scss`:

```
/assets/scss/
  ├── _variables.scss
  ├── _mixins.scss
  ├── _animations.scss
  ├── _base.scss
  ├── _form.scss
  ├── _event-single.scss
  ├── _archive.scss
  └── main.scss
```

Compiled output: `satori-events-main.css`

---

## 💡 Guidelines Summary

- Consistent naming helps with search, readability, and reuse
- Avoid overly short prefixes like `sec` unless branding dictates it
- Prefix everything to avoid conflicts in shared WordPress environments

---

_Authored by: Satori Graphics Pty Ltd_  
_Last Updated: July 2025_
