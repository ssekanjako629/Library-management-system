<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $loan_id = filter_input(INPUT_POST, "loan_id", FILTER_VALIDATE_INT);

    if (!$loan_id) {
        die("Invalid loan ID.");
    }

    try {
        $conn->beginTransaction();

        // Find the loan
        $stmt = $conn->prepare(
            "SELECT book_id, date_returned
             FROM loans
             WHERE loan_id = ?
             FOR UPDATE"
        );

        $stmt->execute([$loan_id]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$loan) {
            throw new Exception("Loan record not found.");
        }

        if ($loan["date_returned"] !== null) {
            throw new Exception("This book has already been returned.");
        }

        // Record the return date
        $stmt = $conn->prepare(
            "UPDATE loans
             SET date_returned = CURDATE()
             WHERE loan_id = ?"
        );

        $stmt->execute([$loan_id]);

        // Increase available copies
        $stmt = $conn->prepare(
            "UPDATE books
             SET available_copies = available_copies + 1
             WHERE book_id = ?"
        );

        $stmt->execute([$loan["book_id"]]);

        $conn->commit();

        header("Location: index.php");
        exit();

    } catch (Exception $e) {

        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

        die("Return failed: " . htmlspecialchars(
            $e->getMessage(),
            ENT_QUOTES,
            "UTF-8"
        ));
    }

} else {
    header("Location: index.php");
    exit();
}
?>