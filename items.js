$(document).ready(function() {
    // Open the edit modal
    $('.btn-edit').on('click', function() {
        var itemId = $(this).data('id');
        $.ajax({
            url: 'get_item.php',
            type: 'GET',
            data: { id: itemId },
            success: function(response) {
                if (response) {
                    $('#editItemId').val(response.id);
                    $('#editName').val(response.name);
                    $('#editDescription').val(response.description);
                    $('#editSG_NSG').val(response.SG_NSG);
                    $('#editRateOpenline').val(response.rate_openline);
                    $('#editRateConstruction').val(response.rate_construction);
                    $('#editRateForeign').val(response.rate_foreign);
                    $('#editSG_NSG_Number').val(response.SG_NSG_Number);
                    $('#editModal').show(); // Show modal
                } else {
                    showMessage('Error fetching item details', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });

    // Close the modal when the user clicks on <span> (x)
    $('.close').on('click', function() {
        $('#editModal').hide(); // Hide modal
        $('#message').hide(); // Hide message
    });

    // Close the modal when the user clicks outside of the modal
    $(window).on('click', function(event) {
        if ($(event.target).is('#editModal')) {
            $('#editModal').hide(); // Hide modal
            $('#message').hide(); // Hide message
        }
    });

    // Handle form submission
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'update_item.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showMessage('Item updated successfully', 'success');
                    setTimeout(function() {
                        $('#editModal').hide(); // Hide modal on success
                        location.reload(); // Reload the page to see the updated item
                    }, 2000); // Delay hiding modal and reloading for message display
                } else {
                    showMessage('Error updating item', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });

    // Handle Delete button click
    $('.btn-delete').on('click', function() {
        var itemId = $(this).data('id');
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: 'delete_item.php',
                type: 'POST',
                data: { id: itemId },
                success: function(response) {
                    if (response.success) {
                        $('tr[data-id="' + itemId + '"]').remove();
                    } else {
                        showMessage('Error deleting item', 'error');
                    }
                },
                error: function() {
                    showMessage('Error processing request', 'error');
                }
            });
        }
    });

    // Function to show messages
    function showMessage(message, type) {
        var messageDiv = $('#message');
        messageDiv.text(message);
        messageDiv.removeClass('success error');
        messageDiv.addClass(type);
        messageDiv.show();
    }
});
