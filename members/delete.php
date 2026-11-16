
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$member_id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$member_id) {
    die("Invalid member ID.");
}

try {
    $stmt = $conn->prepare(
        "DELETE FROM members WHERE member_id = ?"
    );

    $stmt->execute([$member_id]);

    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    die("This member cannot be deleted because they may have loan records.");
}
?>
