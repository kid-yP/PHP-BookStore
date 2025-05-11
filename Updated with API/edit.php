<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        #message {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <h1 class="mb-4">Edit Book</h1>
    <form id="editBookForm">
        <input type="hidden" id="id" name="id">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre:</label>
            <input type="text" class="form-control" id="genre" name="genre">
        </div>
        <div class="form-group">
            <label for="published_year">Published Year:</label>
            <input type="number" class="form-control" id="published_year" name="published_year" min="1900" max="2100">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Book</button>
        <a href="index.php" class="btn btn-secondary mt-3 ml-2">Cancel</a>
    </form>
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

            // Load book data from API
            $.get(`api.php?id=${bookId}`, function(book) {
                if (book.message) {
                    $('#message').html(`<div class="alert alert-danger">${book.message}</div>`);
                    return;
                }
                
                // Populate form with book data
                $('#id').val(book.id);
                $('#title').val(book.title);
                $('#author').val(book.author);
                $('#genre').val(book.genre);
                $('#published_year').val(book.published_year);
            }).fail(function() {
                $('#message').html('<div class="alert alert-danger">Error loading book data</div>');
            });

            // Handle form submission
            $('#editBookForm').submit(function(e) {
                e.preventDefault();
                
                // Show loading state
                $('button[type="submit"]').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
                );
                
                // Prepare form data
                const formData = {
                    id: $('#id').val(),
                    title: $('#title').val(),
                    author: $('#author').val(),
                    genre: $('#genre').val(),
                    published_year: $('#published_year').val()
                };

                // Send PUT request to API
                $.ajax({
                    url: 'api.php',
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
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
                            `<div class="alert alert-danger">Error updating book. Please try again.</div>`
                        );
                        console.error('Error:', error);
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).html('Update Book');
                    }
                });
            });
        });
    </script>
</body>

</html>