
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";
require_once "../includes/functions.php";

// Get library statistics
$totalBooks = $conn->query("SELECT COUNT(*) FROM books")->fetchColumn();

$totalMembers = $conn->query("SELECT COUNT(*) FROM members")->fetchColumn();

$totalLoans = $conn->query("SELECT COUNT(*) FROM loans")->fetchColumn();

$borrowedBooks = $conn->query(
    "SELECT COUNT(*) FROM loans WHERE date_returned IS NULL"
)->fetchColumn();

$overdueBooks = $conn->query(
    "SELECT COUNT(*) FROM loans
     WHERE date_returned IS NULL AND due_date < CURDATE()"
)->fetchColumn();
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Library Dashboard</h1>
    <p>Welcome to the Library Management System.</p>

    <div class="dashboard-cards">

        <div class="card">
            <h3>Total Books</h3>
            <p><?php echo clean($totalBooks); ?></p>
        </div>

        <div class="card">
            <h3>Total Members</h3>
            <p><?php echo clean($totalMembers); ?></p>
        </div>

        <div class="card">
            <h3>Total Loans</h3>
            <p><?php echo clean($totalLoans); ?></p>
        </div>

        <div class="card">
            <h3>Books Borrowed</h3>
            <p><?php echo clean($borrowedBooks); ?></p>
        </div>

        <div class="card">
            <h3>Overdue Books</h3>
            <p><?php echo clean($overdueBooks); ?></p>
        </div>

    </div>

    <p><a href="../auth/logout.php">Logout</a></p>
</main>

<?php include "../includes/footer.php"; ?>
