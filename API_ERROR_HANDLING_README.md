# API Error Handling System

This Laravel application includes a comprehensive API error handling system that provides consistent error responses and user-friendly error display.

## Features

- **Global Exception Handler**: Automatically handles all API exceptions and returns consistent JSON responses
- **Custom Exception Classes**: Pre-built exception classes for common error types
- **JavaScript Error Handler**: Client-side error handling with notifications and field validation
- **Form Integration**: Easy-to-use form handlers that integrate with the error system

## Backend (PHP) Error Handling

### Global Exception Handler

The `app/Exceptions/Handler.php` file handles all exceptions globally for API routes. It provides:

- Consistent JSON error responses
- Different error handling based on exception type
- Development vs production error details

### Custom Exception Classes

Use these custom exceptions in your controllers for better error categorization:

```php
use App\Exceptions\ApiException;
use App\Exceptions\ValidationException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;

// Example usage in a controller
public function store(Request $request)
{
    // Validation failed
    if (!$request->has('name')) {
        throw new ValidationException(['name' => 'Name is required']);
    }

    // Access denied
    if (!$user->can('create-posts')) {
        throw new ForbiddenException('You cannot create posts');
    }

    // Resource not found
    $post = Post::find($id);
    if (!$post) {
        throw new NotFoundException('Post not found');
    }

    // Custom error
    throw new ApiException('Custom error message', 400, ['field' => 'Custom field error']);
}
```

### API Response Format

All API responses follow this consistent format:

**Success Response:**
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Error message 1", "Error message 2"]
    }
}
```

## Frontend (JavaScript) Error Handling

### Automatic Error Handling

The `ApiErrorHandler` class automatically handles axios errors and displays them appropriately:

- **Toast notifications** for general errors (using SweetAlert2 if available)
- **Field validation errors** displayed under form fields
- **Automatic redirects** for authentication errors

### Using the API Error Handler

```javascript
// Make API calls - errors are handled automatically
try {
    const response = await window.apiErrorHandler.post('/api/products', productData);
    console.log('Success:', response);
} catch (error) {
    // Error already displayed to user
    console.error('API call failed:', error);
}

// Available methods
const data = await window.apiErrorHandler.get('/api/products');
const created = await window.apiErrorHandler.post('/api/products', newProduct);
const updated = await window.apiErrorHandler.put('/api/products/1', updateData);
const result = await window.apiErrorHandler.delete('/api/products/1');
```

### Form Integration

Use the `FormHandler` class to automatically handle form submissions with API error display:

```javascript
// Initialize a form with API error handling
const productForm = new FormHandler('#product-form', '/api/products', 'POST');

// Or initialize all forms automatically
initializeApiForms(); // Called automatically on DOM ready
```

### Manual Error Display

You can manually trigger error display:

```javascript
// Show general error
window.apiErrorHandler.showNotification('Something went wrong', 'error');

// Show field-specific errors
window.apiErrorHandler.showError('Validation failed', {
    'email': 'Email is already taken',
    'password': 'Password must be at least 8 characters'
});

// Clear all errors
window.apiErrorHandler.clearErrors();
```

## Configuration

### Axios Setup

Axios interceptors are automatically configured in `resources/js/api-errors.js`. The system:

- Handles all HTTP errors automatically
- Shows appropriate notifications
- Handles authentication redirects
- Supports both JSON and form data requests

### Dependencies

Make sure these are included in your templates:

```html
<!-- jQuery (for Bootstrap components) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 (optional, for better notifications) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Your compiled JavaScript -->
<script src="{{ mix('js/app.js') }}"></script>
```

## Error Types and HTTP Status Codes

| Exception Type | HTTP Status | Description |
|----------------|-------------|-------------|
| ValidationException | 422 | Form validation errors |
| UnauthorizedException | 401 | Authentication required |
| ForbiddenException | 403 | Access denied |
| NotFoundException | 404 | Resource not found |
| ApiException | Custom | General API errors |

## Best Practices

1. **Use Custom Exceptions**: Instead of generic exceptions, use the specific exception classes for better error categorization.

2. **Consistent Error Messages**: Keep error messages user-friendly and consistent across the application.

3. **Handle Loading States**: Use the form handler's loading states to provide user feedback during API calls.

4. **Validate on Frontend**: While backend validation is crucial, add frontend validation to improve user experience.

5. **Test Error Scenarios**: Make sure to test various error scenarios to ensure proper error handling.

## Examples

### Controller Example

```php
<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;
use App\Models\Product;

class ProductController extends Controller
{
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            throw new NotFoundException('Product not found');
        }

        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->toArray());
        }

        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }
}
```

### Frontend Form Example

```html
<form id="product-form" action="/api/products" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
        <div class="invalid-feedback"></div>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" required>
        <div class="invalid-feedback"></div>
    </div>

    <button type="submit" class="btn btn-primary">Save Product</button>
</form>

<script>
// Initialize form with API error handling
document.addEventListener('DOMContentLoaded', function() {
    new FormHandler('#product-form', '/api/products', 'POST');
});
</script>
```

This system provides a robust, user-friendly way to handle API errors throughout your Laravel application.
