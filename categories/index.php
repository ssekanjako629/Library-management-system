
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";
require_once "../includes/functions.php";

$stmt = $conn->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Categories Management</h1>

    <p><a href="add.php">Add New Category</a></p>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Category ID</th>
            <th>Name</th>
            <th>Description</th>
        </tr>

        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?php echo clean($category["category_id"]); ?></td>
            <td><?php echo clean($category["name"]); ?></td>
            <td><?php echo clean($category["description"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include "../includes/footer.php"; ?>
