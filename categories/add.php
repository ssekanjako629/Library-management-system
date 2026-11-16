
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);

    if ($name === "") {
        $error = "Category name is required.";
    } else {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO categories (name, description) VALUES (?, ?)"
            );

            $stmt->execute([
                $name,
                $description ?: null
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not add this category. The name may already exist.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Add New Category</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Category Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4"></textarea><br><br>

        <button type="submit">Save Category</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
