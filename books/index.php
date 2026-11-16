
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";
require_once "../includes/functions.php";

$sql = "SELECT books.*, authors.name AS author_name,
        categories.name AS category_name
        FROM books
        INNER JOIN authors ON books.author_id = authors.author_id
        INNER JOIN categories ON books.category_id = categories.category_id
        ORDER BY books.title";

$stmt = $conn->query($sql);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Books Management</h1>

    <p><a href="add.php">Add New Book</a></p>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Book ID</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Total Copies</th>
            <th>Available Copies</th>
        </tr>

        <?php foreach ($books as $book): ?>
        <tr>
            <td><?php echo clean($book["book_id"]); ?></td>
            <td><?php echo clean($book["isbn"]); ?></td>
            <td><?php echo clean($book["title"]); ?></td>
            <td><?php echo clean($book["author_name"]); ?></td>
            <td><?php echo clean($book["category_name"]); ?></td>
            <td><?php echo clean($book["total_copies"]); ?></td>
            <td><?php echo clean($book["available_copies"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</main>

<?php include "../includes/footer.php"; ?>
