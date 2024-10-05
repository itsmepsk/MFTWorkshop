$(document).ready(function() {
    // Event listener for the edit button
    $('.btn-edit').on('click', function() {
        var unitId = $(this).data('id');
        // Fetch zone details and open the modal
        var unitName = $(this).closest('tr').find('td:eq(2)').text();
        var unitCode = $(this).closest('tr').find('td:eq(1)').text();
        var zone = $(this).closest('tr').find('td:eq(4)').text();
        // console.log("Zone = "+zone);
        $('#editUnitId').val(unitId);
        $('#editUnitName').val(unitName);
        $('#editUnitCode').val(unitCode);
        $('#editZone').val(zone);

        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { get_unit_id: unitId },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    var zoneSelect = $('#ediZOne');
                    zoneSelect.empty(); // Clear existing options
                    // console.log(response);
                    
                    populateDropdown('editZone', response['zones'], zone);

                    // Set the current indentor as the selected option
                    zoneSelect.val(zone);
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

    function populateDropdown(dropdownId, options, selectedValue) {
        let dropdown = $('#' + dropdownId);
        dropdown.empty();  // Clear existing options
        // console.log(options);
        // Loop through the options and create option elements
        options.forEach(option => {
            let isSelected = String(option.id) === String(selectedValue) ? 'selected' : '';

            // console.log('<option value="' + option.id + '" ' + isSelected + '>' + option.name + '</option>');
            dropdown.append('<option value="' + option.id + '" ' + isSelected + ' >' + option.name + '</option>');
        });

        // dropdown.val(selectedValue); 
    }

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
        const data = $(this).serialize() + '&entity_type=unit';
        $.ajax({
            url: 'update_data.php',
            type: 'POST',
            data: data,
            success: function(response) {
                console.log(response.success);
                if (response.success) {
                    showMessage('Unit updated successfully', 'success');
                    setTimeout(function() {
                        $('#editModal').hide();
                        location.reload();
                    }, 2000);
                } else {
                    showMessage('Error updating unit', 'error');
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
                entity_type: 'unit' // Specify the entity type
            };

            $.ajax({
                url: 'delete_data.php',
                type: 'POST',
                data: data, // Send data as an object
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        $('tr[data-id="' + zoneId + '"]').remove();
                        showDeleteMessage('Unit deleted successfully', 'success');
                    } else {
                        showDeleteMessage('Error deleting unit: ' + response.message, 'error');
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
