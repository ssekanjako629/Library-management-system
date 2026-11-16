
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$author_id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$author_id) {
    die("Invalid author ID.");
}

try {
    $stmt = $conn->prepare(
        "DELETE FROM authors WHERE author_id = ?"
    );

    $stmt->execute([$author_id]);

    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    die("This author cannot be deleted because they may have books registered under their name.");
}
?>
