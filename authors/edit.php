
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$author_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$author_id) {
    die("Invalid author ID.");
}

$stmt = $conn->prepare("SELECT * FROM authors WHERE author_id = ?");
$stmt->execute([$author_id]);
$author = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$author) {
    die("Author not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $country = trim($_POST["country"]);

    if ($name === "") {
        $error = "Author name is required.";
    } else {
        try {
            $stmt = $conn->prepare(
                "UPDATE authors SET name = ?, country = ? WHERE author_id = ?"
            );

            $stmt->execute([
                $name,
                $country ?: null,
                $author_id
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not update this author. Please try again.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Edit Author</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Author Name:</label><br>
        <input type="text" name="name"
               value="<?php echo htmlspecialchars($author["name"]); ?>"
               required><br><br>

        <label>Country:</label><br>
        <input type="text" name="country"
               value="<?php echo htmlspecialchars($author["country"] ?? ""); ?>"><br><br>

        <button type="submit">Update Author</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
