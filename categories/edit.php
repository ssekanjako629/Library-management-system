
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$category_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$category_id) {
    die("Invalid category ID.");
}

$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Category not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);

    if ($name === "") {
        $error = "Category name is required.";
    } else {
        try {
            $stmt = $conn->prepare(
                "UPDATE categories SET name = ?, description = ? WHERE category_id = ?"
            );

            $stmt->execute([
                $name,
                $description ?: null,
                $category_id
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not update this category. The name may already exist.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Edit Category</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Category Name:</label><br>
        <input type="text" name="name"
               value="<?php echo htmlspecialchars($category["name"]); ?>"
               required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($category["description"] ?? ""); ?></textarea><br><br>

        <button type="submit">Update Category</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
