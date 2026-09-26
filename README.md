# CrudAdmin Gutenberg
This package contains of all resources to support wordpress gutenberg field type in crudadmin system.

## Requirements

- CrudAdmin 6 (`crudadmin/crudadmin` `^6.0`), PHP 8.3+.

## Installation

```bash
composer require crudadmin/gutenberg
php artisan admin:update
```

`php artisan admin:update` publishes the editor assets (`public/vendor/gutenberg`) with the `admin.resources` tag and adds the directory into `.gitignore`. They can be published alone with `php artisan vendor:publish --tag=admin_gutenberg.assets`.

React 18 is loaded from unpkg.com.

## Integrations

- Custom editor integrations should dispatch `new CustomEvent('crudadmin:disable-gutenberg-editors', { detail: { id, callback } })` on `window` when switching the active editor. Listeners read `event.detail` and must be removed when the component unmounts.
- The Gutenberg JavaScript API (`init`, `getContent`, `setContent`) remains unchanged.
