<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .btn a {
            color: white;
            text-decoration: none;
        }
        .book-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .action-links a {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1>Book List</h1>
        <button class="btn btn-primary mb-3"><a href="add.php">Add New Book</a></button>
        <button class="btn btn-primary mb-3"><a href="search.php">Search Book</a></button>

        <div id="bookList"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchBooks();
        });

        function fetchBooks() {
            fetch('api.php')
                .then(response => response.json())
                .then(books => {
                    const bookList = document.getElementById('bookList');
                    bookList.innerHTML = '';
                    
                    if (books.length === 0) {
                        bookList.innerHTML = '<p>No books found.</p>';
                        return;
                    }
                    
                    books.forEach(book => {
                        const bookItem = document.createElement('div');
                        bookItem.className = 'book-item';
                        bookItem.innerHTML = `
                            <strong>${book.title}</strong> by ${book.author} (${book.published_year})
                            <div class="action-links">
                                <a href="edit.php?id=${book.id}" class="btn btn-sm btn-warning">Edit</a>
                                <button onclick="deleteBook(${book.id})" class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        `;
                        bookList.appendChild(bookItem);
                    });
                })
                .catch(error => {
                    console.error('Error fetching books:', error);
                    document.getElementById('bookList').innerHTML = '<p>Error loading books. Please try again later.</p>';
                });
        }

        function deleteBook(id) {
            if (confirm('Are you sure you want to delete this book?')) {
                fetch(`api.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Book deleted successfully');
                    fetchBooks(); // Refresh the book list
                })
                .catch(error => {
                    console.error('Error deleting book:', error);
                    alert('Error deleting book');
                });
            }
        }
    </script>
</body>

</html>