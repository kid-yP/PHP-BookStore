<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .search-container {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .book-item {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
        }
        .book-title {
            font-weight: bold;
            font-size: 1.1em;
        }
        .book-author {
            color: #6c757d;
        }
        .action-links {
            margin-top: 10px;
        }
        #searchResults {
            margin-top: 20px;
        }
        #loading {
            display: none;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1 class="mb-4">Search Books</h1>
    <a href="index.php" class="btn btn-secondary mb-4">Back to Book List</a>
    
    <div class="search-container">
        <h3>Search Criteria</h3>
        <form id="searchForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="published_year" class="form-label">Published Year</label>
                        <input type="number" class="form-control" id="published_year" name="published_year" min="1900" max="2100">
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" id="resetSearch" class="btn btn-outline-secondary ms-2">Reset</button>
                </div>
            </div>
        </form>
    </div>

    <div id="loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Searching books...</p>
    </div>

    <div id="searchResults"></div>

    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#searchForm').submit(function(e) {
                e.preventDefault();
                searchBooks();
            });

            // Handle reset button
            $('#resetSearch').click(function() {
                $('#searchForm')[0].reset();
                $('#searchResults').empty();
            });

            // Function to search books
            function searchBooks() {
                // Show loading indicator
                $('#loading').show();
                $('#searchResults').empty();

                // Get form data
                const searchParams = {
                    title: $('#title').val(),
                    author: $('#author').val(),
                    genre: $('#genre').val(),
                    published_year: $('#published_year').val()
                };

                // Filter out empty parameters
                const filteredParams = Object.fromEntries(
                    Object.entries(searchParams).filter(([_, v]) => v !== '')
                );

                // Convert to query string
                const queryString = new URLSearchParams(filteredParams).toString();

                // Fetch books from API
                $.get(`api.php?${queryString}`, function(books) {
                    displayResults(books);
                })
                .fail(function(xhr, status, error) {
                    $('#searchResults').html(
                        `<div class="alert alert-danger">Error searching books: ${error}</div>`
                    );
                })
                .always(function() {
                    $('#loading').hide();
                });
            }

            // Function to display search results
            function displayResults(books) {
                const resultsContainer = $('#searchResults');
                resultsContainer.empty();

                if (!books || books.length === 0) {
                    resultsContainer.html(
                        '<div class="alert alert-info">No books found matching your criteria.</div>'
                    );
                    return;
                }

                if (typeof books === 'object' && books.message) {
                    resultsContainer.html(
                        `<div class="alert alert-warning">${books.message}</div>`
                    );
                    return;
                }

                books.forEach(book => {
                    const bookItem = $(`
                        <div class="book-item">
                            <div class="book-title">${book.title}</div>
                            <div class="book-author">by ${book.author}</div>
                            <div>Genre: ${book.genre || 'N/A'}</div>
                            <div>Published: ${book.published_year || 'Unknown year'}</div>
                            <div class="action-links">
                                <a href="edit.php?id=${book.id}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete.php?id=${book.id}" class="btn btn-sm btn-danger">Delete</a>
                            </div>
                        </div>
                    `);
                    resultsContainer.append(bookItem);
                });
            }

            // Optional: Load all books on initial page load
            // searchBooks();
        });
    </script>
</body>

</html>