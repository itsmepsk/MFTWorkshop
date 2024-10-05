$(document).ready(function() {

    // Filter functionality
    // $('.filter-input').on('keyup', function() {
    //     // Get the column index to filter
    //     console.log("Yay! Key Up");
    //     let column = $(this).data('column');
    //     // Get the filter value (input text)
    //     // console.log(column);
    //     let filterValue = $(this).val().toLowerCase();
    //     // console.log(filterValue);
    //     // Loop through each row in the table
    //     $('#workOrderTable tbody tr').filter(function() {
    //         // Toggle the visibility of each row based on the filter value
    //         // $(this).toggle($(this).find('td').eq(column).text().toLowerCase().indexOf(filterValue) > -1);
    //         var cellText = $(this).find('td').eq(column).text().toLowerCase();
    //         console.log('Cell Text: ', cellText, ' | Filter Value: ', filterValue);
    //         $(this).toggle(cellText.indexOf(filterValue) > -1);
    //     });
    // });

    function populateExcelFilters() {
        $('#workOrderTable thead tr:eq(1) th').each(function(index) {
            var columnValues = [];
            // Collect unique values from each column in the tbody
            $('#workOrderTable tbody tr').each(function() {
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
            $('#workOrderTable tbody tr').filter(function() {
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
        $('#workOrderTable tbody tr').each(function() {
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

    

    function filterTable() {
        $('#workOrderTable tbody tr').each(function() {
            let isVisible = true; // Assume the row is visible by default
            
            // Loop through each filter input and dropdown
            $('.filter-input, .excel-filter').each(function() {
                let column = $(this).data('column'); // Get the column index
                let filterValue = $(this).val().toLowerCase(); // Get the filter value

                // Get the text of the cell in the corresponding column
                let cellText = $(this).closest('table').find('tbody tr').find('td').eq(column).text().toLowerCase();

                // Check if the filter is a text input
                if ($(this).hasClass('filter-input')) {
                    $(this).toggle(cellText.indexOf(filterValue) > -1);
                }

                // Check if the filter is a dropdown
                if ($(this).hasClass('excel-filter') && filterValue !== "") {
                    if (cellText !== filterValue) {
                        isVisible = false; // Cell doesn't match, set row to not visible
                    }
                }
            });

            // Toggle visibility of the row based on the filter results
            $(this).toggle(isVisible);
        });
    }

    // Event listener for the edit button
    $('.btn-edit').on('click', function() {
        var workOrderId = $(this).data('id');
        // Fetch work order details and open the modal
        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            data: { get_workorder_id: workOrderId },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    // console.log(response);
                    // Populate modal fields with fetched data
                    $('#workOrderId').val(response['work_order'].id);
                    $('#workOrderNumber').val(response['work_order'].work_order_number);
                    $('#quantity').val(response['work_order'].quantity);

                     // Format and set the date (assuming dd-mm-yyyy in the response)
                     let formattedDate = convertToYYYYMMDD(response['work_order'].work_order_date);
                     $('#date').val(formattedDate);


                    $('#jobNumber').val(response['work_order'].job_number);
                    $('#folioNumber').val(response['work_order'].folio_number);
                    // console.log(response['items']);
                    populateDropdown('item', response['items'], response['work_order'].item);
                    populateDropdown('indentor', response['indentors'], response['work_order'].indentor);
                    populateDropdown('consignee', response['consignees'], response['work_order'].consignee);
                    populateDropdown('accountingUnit', response['accounting_units'], response['work_order'].accounting_unit);
                    populateDropdown('unit', response['units'], response['work_order'].unit);

                    $('#item').val(response['work_order'].item);
                    $('#indentor').val(response['work_order'].indentor);
                    $('#consignee').val(response['work_order'].consignee);
                    $('#unit').val(response['work_order'].unit);
                    $('#allocation').val(response['work_order'].allocation);
                    $('#accountingUnit').val(response['work_order'].accounting_unit);
                    
                    // Show the modal after data is loaded
                    $('#editModal').show();
                } else {
                    showMessage('Error fetching work order details', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });

    function convertToYYYYMMDD(dateStr) {
        let parts = dateStr.split('-');
        return parts[2] + '-' + parts[1] + '-' + parts[0];  // Convert to yyyy-mm-dd
    }

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

    // Close modal on close button click
    $('.close').on('click', function() {
        $('#editModal').hide();
        $('#message').hide();
    });

    // Close modal when clicking outside the modal
    $(window).on('click', function(event) {
        if ($(event.target).is('#editModal')) {
            $('#editModal').hide();
            $('#message').hide();
        }
    });

    // Handle form submission for updating the work order
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize() + '&entity_type=work_order';   
        $.ajax({
            url: 'update_data.php',
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    showMessage('Work order updated successfully', 'success');
                    setTimeout(function() {
                        $('#editModal').hide();
                        location.reload();
                    }, 2000);
                } else {
                    showMessage('Error updating work order', 'error');
                }
            },
            error: function() {
                showMessage('Error processing request', 'error');
            }
        });
    });

    // Event listener for delete button
    $('.btn-delete').off('click').on('click', function() {
        var workOrderId = $(this).data('id');
        if (confirm('Are you sure you want to delete this work order?')) {
            const data = {
                id: workOrderId,
                entity_type: 'work_order'
            };

            $.ajax({
                url: 'delete_data.php',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('tr[data-id="' + workOrderId + '"]').remove();
                        showDeleteMessage('Work order deleted successfully', 'success');
                        setTimeout(function() {
                            $('#editModal').hide();
                            location.reload();
                        }, 2000);
                    } else {
                        showDeleteMessage('Error deleting work order: ' + response.message, 'error');
                    }
                },
                error: function() {
                    showMessage('Error processing request', 'error');
                }
            });
        }
    });

    // Show message for success or error
    function showMessage(message, type) {
        var messageDiv = $('#message');
        messageDiv.text(message);
        messageDiv.removeClass('success error');
        messageDiv.addClass(type);
        messageDiv.show();
    }

    // Show delete message
    function showDeleteMessage(message, type) {
        var deleteMessageDiv = $('#delete_message');
        deleteMessageDiv.text(message).removeClass('success error').addClass(type).show();

        // Optionally hide the message after a few seconds
        setTimeout(function() {
            deleteMessageDiv.hide();
        }, 3000); // 3 seconds delay
    }
});
