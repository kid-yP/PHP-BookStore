<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .confirmation-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        #message {
            margin-top: 15px;
        }
        .btn-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1 class="mb-4">Delete Book</h1>
    <div id="bookInfo" class="confirmation-box">
        <p>Loading book information...</p>
    </div>
    <div class="btn-container">
        <button id="confirmDelete" class="btn btn-danger">Confirm Delete</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </div>
    <div id="message" class="mt-3"></div>

    <script>
        $(document).ready(function() {
            // Get book ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const bookId = urlParams.get('id');
            
            if (!bookId) {
                $('#message').html('<div class="alert alert-danger">No book ID specified</div>');
                return;
            }

            // Load book data from API to show what's being deleted
            $.get(`api.php?id=${bookId}`, function(book) {
                if (book.message) {
                    $('#message').html(`<div class="alert alert-danger">${book.message}</div>`);
                    return;
                }
                
                // Display book information
                $('#bookInfo').html(`
                    <h4>${book.title}</h4>
                    <p>by ${book.author}</p>
                    <p>Genre: ${book.genre || 'N/A'}</p>
                    <p>Published: ${book.published_year || 'Unknown year'}</p>
                `);
            }).fail(function() {
                $('#message').html('<div class="alert alert-danger">Error loading book data</div>');
            });

            // Handle delete confirmation
            $('#confirmDelete').click(function() {
                if (confirm('Are you sure you want to permanently delete this book?')) {
                    // Show loading state
                    $(this).prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...'
                    );
                    
                    // Send DELETE request to API
                    $.ajax({
                        url: `api.php?id=${bookId}`,
                        type: 'DELETE',
                        success: function(data) {
                            if (data.message) {
                                $('#message').html(
                                    `<div class="alert alert-success">${data.message}</div>`
                                );
                                // Redirect after delay
                                setTimeout(() => {
                                    window.location.href = 'index.php';
                                }, 1500);
                            } else if (data.error) {
                                $('#message').html(
                                    `<div class="alert alert-danger">${data.error}</div>`
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#message').html(
                                `<div class="alert alert-danger">Error deleting book. Please try again.</div>`
                            );
                            console.error('Error:', error);
                        },
                        complete: function() {
                            $('#confirmDelete').prop('disabled', false).html('Confirm Delete');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>