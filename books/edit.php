
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$book_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$book_id) {
    die("Invalid book ID.");
}

$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Book not found.");
}

$authors = $conn->query("SELECT * FROM authors ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isbn = trim($_POST["isbn"]);
    $title = trim($_POST["title"]);
    $author_id = $_POST["author_id"];
    $category_id = $_POST["category_id"];
    $publisher = trim($_POST["publisher"]);
    $year = $_POST["year_published"];
    $total_copies = (int) $_POST["total_copies"];

    $borrowed = (int) $book["total_copies"] - (int) $book["available_copies"];

    if ($total_copies < 1 || $total_copies < $borrowed) {
        $error = "Total copies cannot be less than the number currently borrowed.";
    } else {
        $available_copies = $total_copies - $borrowed;

        try {
            $sql = "UPDATE books SET
                    isbn = ?, title = ?, author_id = ?, category_id = ?,
                    publisher = ?, year_published = ?,
                    total_copies = ?, available_copies = ?
                    WHERE book_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $isbn, $title, $author_id, $category_id,
                $publisher ?: null, $year ?: null,
                $total_copies, $available_copies, $book_id
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not update the book. Check whether the ISBN is already in use.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Edit Book</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>ISBN:</label><br>
        <input type="text" name="isbn"
               value="<?php echo htmlspecialchars($book["isbn"]); ?>" required><br><br>

        <label>Book Title:</label><br>
        <input type="text" name="title"
               value="<?php echo htmlspecialchars($book["title"]); ?>" required><br><br>

        <label>Author:</label><br>
        <select name="author_id" required>
            <?php foreach ($authors as $author): ?>
                <option value="<?php echo $author["author_id"]; ?>"
                    <?php echo $author["author_id"] == $book["author_id"] ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($author["name"]); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Category:</label><br>
        <select name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category["category_id"]; ?>"
                    <?php echo $category["category_id"] == $book["category_id"] ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($category["name"]); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Publisher:</label><br>
        <input type="text" name="publisher"
               value="<?php echo htmlspecialchars($book["publisher"] ?? ""); ?>"><br><br>

        <label>Year Published:</label><br>
        <input type="number" name="year_published" min="1901" max="2155"
               value="<?php echo htmlspecialchars($book["year_published"] ?? ""); ?>"><br><br>

        <label>Total Copies:</label><br>
        <input type="number" name="total_copies" min="1"
               value="<?php echo (int) $book["total_copies"]; ?>" required><br><br>

        <button type="submit">Update Book</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
