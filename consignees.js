$(document).ready(function() {
    // Initialize DataTable for sorting and filtering
    // $('#consigneesTable').DataTable();

    function populateExcelFilters() {
        $('#consigneesTable thead tr:eq(1) th').each(function(index) {
            var columnValues = [];
            // Collect unique values from each column in the tbody
            $('#consigneesTable tbody tr').each(function() {
                var cellText = $(this).find('td').eq(index).text().trim();
                if (cellText !== "" && $.inArray(cellText, columnValues) === -1) {
                    columnValues.push(cellText);  // Add to array if not already added
                }
            });

            // Sort the values (optional)
            columnValues.sort();

            // Populate the Excel-like filter dropdown for the current column
            var selectFilter = $(this).find('.excel-filter');
            selectFilter.empty(); // Clear existing options
            selectFilter.append('<option value="">All</option>');
            columnValues.forEach(function(value) {
                selectFilter.append('<option value="' + value + '">' + value + '</option>');
            });
        });
    }

    // Call the function to populate filters on page load
    populateExcelFilters();

    // Event listener for text input filtering
    $('.filter-input').on('keyup', function() {
        // Call filter function on keyup
        // filterTable();
        // $('.filter-input').on('keyup', function() {
            // Get the column index to filter
            console.log("Yay! Key Up");
            let column = $(this).data('column');
            // Get the filter value (input text)
            // console.log(column);
            let filterValue = $(this).val().toLowerCase();
            // console.log(filterValue);
            // Loop through each row in the table
            $('#consigneesTable tbody tr').filter(function() {
                // Toggle the visibility of each row based on the filter value
                // $(this).toggle($(this).find('td').eq(column).text().toLowerCase().indexOf(filterValue) > -1);
                var cellText = $(this).find('td').eq(column).text().toLowerCase();
                console.log('Cell Text: ', cellText, ' | Filter Value: ', filterValue);
                $(this).toggle(cellText.indexOf(filterValue) > -1);
            });
        // });
    });

    // Event listener for dropdown filtering
    $('.excel-filter').on('change', function() {
        // Get the column index to filter from the data attribute
        let column = $(this).data('column');
        // Get the selected filter value from the dropdown
        let filterValue = $(this).val().toLowerCase(); // Get the selected value from the dropdown

        console.log('Selected Filter Value:', filterValue, '| Column:', column);

        // Loop through each row in the table's tbody
        $('#consigneesTable tbody tr').each(function() {
            // Get the text content of the cell in the specified column
            let cellText = $(this).find('td').eq(column).text().toLowerCase();

            console.log('Cell Text:', cellText, '| Filter Value:', filterValue);

            // Check if the cell text matches the filter value or if filter value is empty (for reset)
            if (filterValue === "" || cellText.indexOf(filterValue) > -1) {
                // Show the row if it matches
                $(this).show();
            } else {
                // Hide the row if it doesn't match
                $(this).hide();
            }
        });
    });

    


    // Handle delete action
    $('.btn-delete').on('click', function() {
        var consigneeId = $(this).data('id');
        if (confirm('Are you sure you want to delete this consignee?')) {
            // Perform AJAX request to delete the consignee
            $.ajax({
                url: 'delete_data.php',
                type: 'POST',
                data: { id: consigneeId, entity_type: 'consignee' },
                success: function(response) {
                    if(response.success) {
                        showDeleteMessage('Consignee deleted successfully', 'success');
                        $('tr[data-id="' + consigneeId + '"]').remove();
                    }
                    else {
                        showDeleteMessage('Error Deleting Consignee', 'error');
                    }
                },
                error: function() {
                    showDeleteMessage('Error processing request', 'error');
                }
            });
        }
    });

    // Handle edit action
    $('.btn-edit').on('click', function() {
        var consigneeId = $(this).data('id');
        
        // Get the current row data
        var consigneeName = $(this).closest('tr').find('td:eq(2)').text();
        var consigneeCode = $(this).closest('tr').find('td:eq(1)').text();
        var indentorID = $(this).closest('tr').find('td:eq(4)').text();
        // console.log(indentorID);
        
        // Open the modal and populate the fields
        $('#editConsigneeId').val(consigneeId);
        $('#editConsigneeName').val(consigneeName);
        $('#editConsigneeCode').val(consigneeCode);
        
        // Fetch all indentors and populate the select dropdown
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { 'consignees': consigneeId }, // Use an appropriate ID or filter based on your DB structure
            dataType: 'json',
            success: function(response) {
                var indentorSelect = $('#editIndentor');
                indentorSelect.empty(); // Clear existing options
                console.log(response);
                // Loop through the indentors and append options to the select element
                response['indentors'].forEach(function(indentor) {
                    indentorSelect.append(new Option(indentor.indentor_name, indentor.id));
                });

                // Set the current indentor as the selected option
                indentorSelect.val(indentorID);
            }
        });

        $('#editModal').show();
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
        deleteMessageDiv.text(message).addClass(type).show();
    
        // Optionally hide the message after a few seconds
        // setTimeout(function() {
        //     deleteMessageDiv.hide();
        // }, 3000); // 3 seconds delay
    }

    // Close the modal
    $('.close').on('click', function() {
        $('#editModal').hide();
    });

    // Handle form submission for edit
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize() + '&entity_type=consignee';
        // Perform AJAX request to update the consignee
        $.ajax({
            url: 'update_data.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // console.log(response);
                if (response.success) {
                    showMessage('Consignee updated successfully', 'success');
                    setTimeout(function() {
                        $('#editModal').hide();
                        location.reload();
                    }, 2000);
                } else {
                    showMessage('Error updating Consignee', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });
});
