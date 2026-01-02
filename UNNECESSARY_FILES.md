# Unnecessary Files That Can Be Deleted

## Temporary Cache Files
These are automatically generated and can be safely deleted:
- `bootstrap/cache/*.tmp` - Temporary cache files (6 files)
  - pac2CFA.tmp
  - pac2CFD.tmp
  - pac2CFF.tmp
  - pacFC6D.tmp
  - ser2D76.tmp
  - ser2D79.tmp

## Unused View Files
- `resources/views/empty.blade.php` - Template file that's not being used anywhere in the codebase

## Unused Controller
- `app/Http/Controllers/OrderItemController.php` - Empty controller with no methods implemented, not used in routes
  - Note: Already removed from routes/web.php imports

## Development Files (Can be regenerated)
- `public/hot` - Vite hot reload file (regenerated automatically)
- `bootstrap/cache/packages.php` - Can be regenerated with `php artisan optimize`
- `bootstrap/cache/services.php` - Can be regenerated with `php artisan optimize`

## Recommendations
1. **Delete temporary files**: All `.tmp` files in `bootstrap/cache/`
2. **Delete unused view**: `resources/views/empty.blade.php`
3. **Delete unused controller**: `app/Http/Controllers/OrderItemController.php` (if you're sure it won't be needed)
4. **Keep cache files**: `packages.php` and `services.php` are useful for performance, but can be regenerated

## Commands to Clean Up
```bash
# Delete temporary files
rm bootstrap/cache/*.tmp

# Delete unused view
rm resources/views/empty.blade.php

# Delete unused controller (optional)
rm app/Http/Controllers/OrderItemController.php

# Regenerate cache files (optional)
php artisan optimize:clear
php artisan optimize
```


