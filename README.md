# CrudAdmin Gutenberg
This package contains of all resources to support wordpress gutenberg field type in crudadmin system.

## 3.0.0 upgrade notes

- The CrudAdmin 6 resources integration no longer uses `$bus` or `useEventHub()`. Update resources together with the Gutenberg integration.
- Custom editor integrations should dispatch `new CustomEvent('crudadmin:disable-gutenberg-editors', { detail: { id, callback } })` on `window` when switching the active editor. Listeners read `event.detail` and must be removed when the component unmounts.
- The Gutenberg JavaScript API (`init`, `getContent`, `setContent`) remains unchanged.
