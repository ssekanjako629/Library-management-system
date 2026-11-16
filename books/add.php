
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";

$authors = $conn->query("SELECT * FROM authors ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isbn = trim($_POST["isbn"]);
    $title = trim($_POST["title"]);
    $author_id = $_POST["author_id"];
    $category_id = $_POST["category_id"];
    $publisher = trim($_POST["publisher"]);
    $year = $_POST["year_published"];
    $copies = (int) $_POST["total_copies"];

    if ($copies < 1) {
        $error = "Total copies must be at least 1.";
    } else {
        try {
            $sql = "INSERT INTO books
                    (isbn, title, author_id, category_id, publisher,
                     year_published, total_copies, available_copies)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $isbn,
                $title,
                $author_id,
                $category_id,
                $publisher ?: null,
                $year ?: null,
                $copies,
                $copies
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not add the book. Check that the ISBN is not already registered.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Add New Book</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>ISBN:</label><br>
        <input type="text" name="isbn" required><br><br>

        <label>Book Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Author:</label><br>
        <select name="author_id" required>
            <option value="">Select Author</option>
            <?php foreach ($authors as $author): ?>
                <option value="<?php echo $author["author_id"]; ?>">
                    <?php echo htmlspecialchars($author["name"]); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category["category_id"]; ?>">
                    <?php echo htmlspecialchars($category["name"]); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Publisher:</label><br>
        <input type="text" name="publisher"><br><br>

        <label>Year Published:</label><br>
        <input type="number" name="year_published" min="1901" max="2155"><br><br>

        <label>Total Copies:</label><br>
        <input type="number" name="total_copies" min="1" value="1" required><br><br>

        <button type="submit">Save Book</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
