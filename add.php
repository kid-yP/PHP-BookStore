<?php
include 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $published_year = $_POST['published_year'];

    // Validate inputs
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($author)) {
        $errors[] = "Author is required.";
    }
    if (!empty($published_year) && ($published_year < 1900 || $published_year > date("Y"))) {
        $errors[] = "Published year must be between 1900 and the current year.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, genre, published_year) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $author, $genre, $published_year]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Add New Book</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST">
        Title: <input type="text" name="title" required><br>
        Author: <input type="text" name="author" required><br>
        Genre: <input type="text" name="genre"><br>
        Published Year: <input type="number" name="published_year" min="1900" max="<?php echo date('Y'); ?>"><br>
        <input type="submit" value="Add Book">
    </form>
</body>
</html>