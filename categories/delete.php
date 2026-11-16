
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$category_id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$category_id) {
    die("Invalid category ID.");
}

try {
    $stmt = $conn->prepare(
        "DELETE FROM categories WHERE category_id = ?"
    );

    $stmt->execute([$category_id]);

    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    die("This category cannot be deleted because books may be assigned to it.");
}
?>
