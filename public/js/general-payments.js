/**
 * General Payments JavaScript
 * Handles delete functionality for general payments
 */

$(document).ready(function() {
    // Handle delete button click
    $(document).on('click', '[data-kt-ecommerce-category-filter="delete_row"]', function(e) {
        e.preventDefault();
        
        const deleteUrl = $(this).data('url');
        const row = $(this).closest('tr');
        
        // Show confirmation dialog
        Swal.fire({
            title: window.deleteTranslations.confirm_delete,
            text: window.deleteTranslations.delete_warning_message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: window.deleteTranslations.yes_delete,
            cancelButtonText: window.deleteTranslations.cancel,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: window.deleteTranslations.processing,
                    text: window.deleteTranslations.please_wait,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Send delete request
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Hide loading
                        Swal.close();
                        
                        // Show success message
                        Swal.fire({
                            title: window.deleteTranslations.success,
                            text: response.message || 'Payment deleted successfully',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Remove the row from the table
                        if (row.length) {
                            row.fadeOut(400, function() {
                                $(this).remove();
                                
                                // Check if table is empty
                                const tbody = $('table tbody');
                                if (tbody.find('tr').length === 0) {
                                    tbody.html('<tr><td colspan="10" class="text-center text-muted">{{ __("dashboard.no_data_found") }}</td></tr>');
                                }
                            });
                        } else {
                            // If we can't find the row, just reload the page
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        // Hide loading
                        Swal.close();
                        
                        let errorMessage = 'An error occurred while deleting the payment.';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            } else if (xhr.status === 404) {
                                errorMessage = 'Payment not found or already deleted.';
                            } else if (xhr.status === 403) {
                                errorMessage = 'You do not have permission to delete this payment.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'A server error occurred. Please try again later.';
                            }
                        } catch (e) {
                            // If we can't parse the response, use the default error message
                        }
                        
                        // Show error message
                        Swal.fire({
                            title: window.deleteTranslations.error || 'Error',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: window.deleteTranslations.ok || 'OK'
                        });
                    }
                });
            }
        });
    });
});
