
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $country = trim($_POST["country"]);

    if ($name === "") {
        $error = "Author name is required.";
    } else {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO authors (name, country) VALUES (?, ?)"
            );

            $stmt->execute([
                $name,
                $country ?: null
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not add this author. Please try again.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Add New Author</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Author Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Country:</label><br>
        <input type="text" name="country"><br><br>

        <button type="submit">Save Author</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
