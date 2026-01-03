/**
 * API Error Handling Utility
 * Handles API responses and displays errors appropriately
 */

class ApiErrorHandler {
    constructor() {
        this.setupAxiosInterceptors();
    }

    /**
     * Setup axios response interceptors to handle errors globally
     */
    setupAxiosInterceptors() {
        if (window.axios) {
            // Response interceptor
            window.axios.interceptors.response.use(
                (response) => {
                    // Handle successful responses
                    this.handleSuccessResponse(response);
                    return response;
                },
                (error) => {
                    // Handle error responses
                    this.handleErrorResponse(error);
                    return Promise.reject(error);
                }
            );
        }
    }

    /**
     * Handle successful API responses
     */
    handleSuccessResponse(response) {
        // You can add success notifications here if needed
        if (response.data && response.data.message) {
            this.showNotification(response.data.message, 'success');
        }
    }

    /**
     * Handle API error responses
     */
    handleErrorResponse(error) {
        let message = 'An unexpected error occurred';
        let errors = {};

        if (error.response) {
            // Server responded with error status
            const { status, data } = error.response;

            switch (status) {
                case 400:
                    message = data.message || 'Bad request';
                    errors = data.errors || {};
                    break;
                case 401:
                    message = data.message || 'Unauthorized access';
                    this.handleUnauthorized();
                    break;
                case 403:
                    message = data.message || 'Access forbidden';
                    break;
                case 404:
                    message = data.message || 'Resource not found';
                    break;
                case 422:
                    message = data.message || 'Validation failed';
                    errors = data.errors || {};
                    break;
                case 500:
                    message = data.message || 'Internal server error';
                    break;
                default:
                    message = data.message || `Error ${status}`;
            }
        } else if (error.request) {
            // Network error
            message = 'Network error - please check your connection';
        }

        this.showError(message, errors);
    }

    /**
     * Handle unauthorized access
     */
    handleUnauthorized() {
        // Redirect to login or show login modal
        if (window.location.pathname !== '/login') {
            window.location.href = '/login';
        }
    }

    /**
     * Show error message and validation errors
     */
    showError(message, errors = {}) {
        // Remove existing error messages
        this.clearErrors();

        // Show main error message
        this.showNotification(message, 'error');

        // Show field-specific errors
        Object.keys(errors).forEach(field => {
            const fieldErrors = errors[field];
            if (Array.isArray(fieldErrors)) {
                fieldErrors.forEach(error => {
                    this.showFieldError(field, error);
                });
            } else {
                this.showFieldError(field, fieldErrors);
            }
        });
    }

    /**
     * Show field-specific error
     */
    showFieldError(field, message) {
        const fieldElement = document.querySelector(`[name="${field}"]`) ||
                           document.querySelector(`[id="${field}"]`) ||
                           document.querySelector(`[data-field="${field}"]`);

        if (fieldElement) {
            // Add error class to field
            fieldElement.classList.add('is-invalid');

            // Create or update error message element
            let errorElement = fieldElement.parentNode.querySelector('.invalid-feedback');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                fieldElement.parentNode.appendChild(errorElement);
            }
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }

    /**
     * Clear all error messages
     */
    clearErrors() {
        // Remove field error classes
        document.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });

        // Hide error messages
        document.querySelectorAll('.invalid-feedback').forEach(element => {
            element.style.display = 'none';
        });

        // Clear any alert containers
        document.querySelectorAll('.alert').forEach(element => {
            if (element.classList.contains('alert-danger') ||
                element.classList.contains('alert-warning')) {
                element.remove();
            }
        });
    }

    /**
     * Show notification message
     */
    showNotification(message, type = 'info') {
        // Use SweetAlert2 if available, otherwise use basic alert
        if (window.Swal) {
            const config = {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            };

            switch (type) {
                case 'success':
                    config.icon = 'success';
                    config.title = message;
                    break;
                case 'error':
                    config.icon = 'error';
                    config.title = message;
                    break;
                case 'warning':
                    config.icon = 'warning';
                    config.title = message;
                    break;
                default:
                    config.icon = 'info';
                    config.title = message;
            }

            window.Swal.fire(config);
        } else {
            // Fallback to basic alert or console
            console.log(`${type.toUpperCase()}: ${message}`);

            // You could also create a simple notification system here
            this.createBasicNotification(message, type);
        }
    }

    /**
     * Create basic notification (fallback when SweetAlert2 not available)
     */
    createBasicNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            display: none;
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        `;

        document.body.appendChild(notification);

        // Show notification
        $(notification).fadeIn();

        // Auto hide after 3 seconds
        setTimeout(() => {
            $(notification).fadeOut(() => notification.remove());
        }, 3000);

        // Handle close button
        notification.querySelector('.close').addEventListener('click', () => {
            $(notification).fadeOut(() => notification.remove());
        });
    }

    /**
     * Make API call with error handling
     */
    async apiCall(method, url, data = null, config = {}) {
        try {
            const response = await window.axios({
                method: method.toUpperCase(),
                url,
                data,
                ...config
            });
            return response.data;
        } catch (error) {
            // Error is already handled by interceptor
            throw error;
        }
    }

    /**
     * GET request
     */
    async get(url, config = {}) {
        return this.apiCall('GET', url, null, config);
    }

    /**
     * POST request
     */
    async post(url, data = {}, config = {}) {
        return this.apiCall('POST', url, data, config);
    }

    /**
     * PUT request
     */
    async put(url, data = {}, config = {}) {
        return this.apiCall('PUT', url, data, config);
    }

    /**
     * DELETE request
     */
    async delete(url, config = {}) {
        return this.apiCall('DELETE', url, null, config);
    }
}

// Initialize the error handler when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.apiErrorHandler = new ApiErrorHandler();
});

// Export for ES6 modules if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ApiErrorHandler;
}
