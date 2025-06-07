// public/js/script.js

document.addEventListener('DOMContentLoaded', function() {
    // Example: Simple confirmation for delete actions
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to delete this item?')) {
                event.preventDefault();
            }
        });
    });
});