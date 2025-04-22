<?php
header("Content-Type: application/json");
include 'db.php';

$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC); 
            echo json_encode($book ? $book : ["message" => "Book not found"]);
        } else {
            $stmt = $pdo->query("SELECT * FROM books");
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            echo json_encode($books);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO books (title, author, genre, published_year) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['title'], $data['author'], $data['genre'], $data['published_year']]);
        echo json_encode(["message" => "Book added successfully"]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'], $data['title'], $data['author'], $data['genre'], $data['published_year'])) {
            $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, genre = ?, published_year = ? WHERE id = ?");
            $stmt->execute([$data['title'], $data['author'], $data['genre'], $data['published_year'], $data['id']]);
            echo json_encode(["message" => "Book updated successfully"]);
        } else {
            echo json_encode(["error" => "Invalid input data"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(["message" => "Book deleted successfully"]);
        } else {
            echo json_encode(["error" => "Book ID required"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}
?>
