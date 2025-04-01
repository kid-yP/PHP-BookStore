<?php
include 'db.php';

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM books");
}
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bookstore</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Book List</h1>
    <form method="GET">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by title or author">
        <input type="submit" value="Search">
    </form>
    <a href="add.php">Add New Book</a>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?php echo htmlspecialchars($book['title']); ?> by <?php echo htmlspecialchars($book['author']); ?>
                <a href="edit.php?id=<?php echo $book['id']; ?>">Edit</a>
                <a href="delete.php?id=<?php echo $book['id']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>