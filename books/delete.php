
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$book_id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$book_id) {
    die("Invalid book ID.");
}

try {
    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->execute([$book_id]);

    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    die("This book cannot be deleted because it may have loan records.");
}
?>
