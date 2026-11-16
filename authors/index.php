
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";
require_once "../includes/functions.php";

$stmt = $conn->query("SELECT * FROM authors ORDER BY name");
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Authors Management</h1>

    <p><a href="add.php">Add New Author</a></p>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Author ID</th>
            <th>Name</th>
            <th>Country</th>
        </tr>

        <?php foreach ($authors as $author): ?>
        <tr>
            <td><?php echo clean($author["author_id"]); ?></td>
            <td><?php echo clean($author["name"]); ?></td>
            <td><?php echo clean($author["country"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include "../includes/footer.php"; ?>
