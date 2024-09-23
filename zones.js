$(document).ready(function() {
    // Event listener for the edit button
    $('.btn-edit').on('click', function() {
        var zoneId = $(this).data('id');
        // Fetch zone details and open the modal
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { get_zone_id: zoneId },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    // Populate modal fields
                    $('#editZoneId').val(response['zone'].id);
                    $('#editZoneName').val(response['zone'].zone_name);
                    $('#editZoneCode').val(response['zone'].zone_code);
                    
                    // Show the modal
                    $('#editModal').show(); // Only show after data is loaded
                } else {
                    showMessage('Error fetching zone details', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });

    $('.close').on('click', function() {
        $('#editModal').hide();
        $('#message').hide();
    });

    $(window).on('click', function(event) {
        if ($(event.target).is('#editModal')) {
            $('#editModal').hide();
            $('#message').hide();
        }
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize() + '&entity_type=zone';
        $.ajax({
            url: 'update_data.php',
            type: 'POST',
            data: data,
            success: function(response) {
                // console.log(response);
                if (response.success) {
                    showMessage('Zone updated successfully', 'success');
                    setTimeout(function() {
                        $('#editModal').hide();
                        location.reload();
                    }, 2000);
                } else {
                    showMessage('Error updating zone', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });

    $('.btn-delete').off('click').on('click', function() {
        var zoneId = $(this).data('id');
        if (confirm('Are you sure you want to delete this zone?')) {
            const data = {
                id: zoneId,
                entity_type: 'zone' // Specify the entity type
            };

            $.ajax({
                url: 'delete_data.php',
                type: 'POST',
                data: data, // Send data as an object
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        $('tr[data-id="' + zoneId + '"]').remove();
                        showDeleteMessage('Zone deleted successfully', 'success');
                    } else {
                        showDeleteMessage('Error deleting zone: ' + response.message, 'error');
                    }
                },
                error: function() {
                    showMessage('Error processing request', 'error');
                }
            });
        }
    });
    

    function showMessage(message, type) {
        var messageDiv = $('#message');
        messageDiv.text(message);
        messageDiv.removeClass('success error');
        messageDiv.addClass(type);
        messageDiv.show();
    }

    function showDeleteMessage(message, type) {
        var deleteMessageDiv = $('#delete_message');
        deleteMessageDiv.text(message).removeClass('success error').addClass(type).show();
    
        // Optionally hide the message after a few seconds
        setTimeout(function() {
            deleteMessageDiv.hide();
        }, 3000); // 3 seconds delay
    }

});
