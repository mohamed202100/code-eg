/**
 * Form Handler with API Error Integration
 * Example of how to handle forms with API calls and error display
 */

class FormHandler {
    constructor(formSelector, apiUrl, method = 'POST') {
        this.form = document.querySelector(formSelector);
        this.apiUrl = apiUrl;
        this.method = method;

        if (this.form) {
            this.initialize();
        }
    }

    initialize() {
        // Prevent default form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        // Clear errors on input change
        this.form.addEventListener('input', (e) => {
            if (e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                const errorElement = e.target.parentNode.querySelector('.invalid-feedback');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    }

    async handleSubmit() {
        if (!this.form) return;

        try {
            // Show loading state
            this.setLoading(true);

            // Collect form data
            const formData = this.getFormData();

            // Make API call
            const response = await this.makeApiCall(formData);

            // Handle success
            this.handleSuccess(response);

        } catch (error) {
            // Error is already handled by ApiErrorHandler
            console.error('Form submission failed:', error);
        } finally {
            // Remove loading state
            this.setLoading(false);
        }
    }

    getFormData() {
        const formData = new FormData(this.form);

        // For non-file data, you might want to convert to JSON
        // const data = Object.fromEntries(formData.entries());

        // Return FormData for file uploads, or convert to JSON for regular data
        return formData;
    }

    async makeApiCall(data) {
        // Use the global API error handler
        if (this.method.toUpperCase() === 'GET') {
            return await window.apiErrorHandler.get(this.apiUrl);
        } else {
            return await window.apiErrorHandler[this.method.toLowerCase()](this.apiUrl, data);
        }
    }

    handleSuccess(response) {
        // Show success message
        if (window.apiErrorHandler) {
            window.apiErrorHandler.showNotification(
                response.message || 'Operation completed successfully',
                'success'
            );
        }

        // Optionally redirect or reset form
        if (response.redirect) {
            window.location.href = response.redirect;
        } else {
            this.form.reset();
        }
    }

    setLoading(loading) {
        const submitButton = this.form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = loading;
            submitButton.innerHTML = loading ?
                '<span class="spinner-border spinner-border-sm" role="status"></span> Loading...' :
                submitButton.getAttribute('data-original-text') || 'Submit';
        }

        // Store original button text
        if (!submitButton.getAttribute('data-original-text')) {
            submitButton.setAttribute('data-original-text', submitButton.innerHTML);
        }
    }
}

// Utility function to initialize forms with API error handling
function initializeApiForms() {
    // Example: Initialize product creation form
    const productForm = new FormHandler('#product-create-form', '/api/products', 'POST');

    // Example: Initialize category creation form
    const categoryForm = new FormHandler('#category-create-form', '/api/categories', 'POST');

    // Add more forms as needed
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeApiForms);

// Export for ES6 modules if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FormHandler;
}
