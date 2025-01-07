$(document).ready(function () {
    const editModal = $('#editModal');
    const deleteModal = $('#deleteModal');
    let deleteId = null;

    // Show messages
    function showMessage(message, type) {
        const messageDiv = $('<div>')
            .addClass(`alert ${type}`)
            .text(message)
            .hide();
        $('.content').prepend(messageDiv);
        messageDiv.fadeIn();

        setTimeout(() => {
            messageDiv.fadeOut(() => messageDiv.remove());
        }, 3000);
    }

    // Open Edit Modal
    $('.edit-button').on('click', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#editId').val(id);
        $('#editName').val(name);
        editModal.show();
    });

    // Handle form submission for updating the page
    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        const data = $(this).serialize() + '&entity_type=page';

        $.ajax({
            url: 'update_data.php',
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    showMessage('Page updated successfully', 'success');
                    setTimeout(function () {
                        $('#editModal').hide();
                        location.reload();
                    }, 2000);
                } else {
                    showMessage(response.message || 'Error updating page', 'error');
                }
            },
            error: function () {
                showMessage('Error processing request', 'error');
            }
        });
    });

    // Open Delete Modal
    $('.delete-button').on('click', function () {
        deleteId = $(this).data('id');
        deleteModal.show();
    });

    // Confirm Deletion
    $('#confirmDelete').on('click', function () {
        if (deleteId) {
            $.post('delete_page.php', { id: deleteId }, function (response) {
                if (response.success) {
                    showMessage('Page deleted successfully', 'success');
                    setTimeout(function () {
                        deleteModal.hide();
                        location.reload();
                    }, 2000);
                } else {
                    showMessage(response.message || 'Error deleting page', 'error');
                }
            }, 'json');
        }
    });

    // Close Modals
    $('.close-button, .cancel-button').on('click', function () {
        $('.modal').hide();
    });
});

function showMessage(message, type) {
    const messageDiv = $('<div>')
        .addClass(`alert ${type}`)
        .text(message)
        .hide();

    $('.content').prepend(messageDiv); // Prepend message to the content area
    messageDiv.fadeIn();

    // Auto-hide the message after 3 seconds
    setTimeout(() => {
        messageDiv.fadeOut(() => messageDiv.remove());
    }, 3000);
}
