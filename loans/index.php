<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";
require_once "../includes/functions.php";

$sql = "SELECT
            loans.loan_id,
            members.full_name,
            books.title,
            loans.date_issued,
            loans.due_date,
            loans.date_returned
        FROM loans
        INNER JOIN members ON loans.member_id = members.member_id
        INNER JOIN books ON loans.book_id = books.book_id
        ORDER BY loans.loan_id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Library Loans</h1>

<p><a href="add.php">Issue a Book</a></p>

<table border="1" cellpadding="8">
    <tr>
        <th>Loan ID</th>
        <th>Member</th>
        <th>Book</th>
        <th>Date Issued</th>
        <th>Due Date</th>
        <th>Date Returned</th>
        <th>Action</th>
    </tr>

    <?php foreach ($loans as $loan): ?>
        <tr>
            <td><?php echo clean($loan["loan_id"]); ?></td>
            <td><?php echo clean($loan["full_name"]); ?></td>
            <td><?php echo clean($loan["title"]); ?></td>
            <td><?php echo clean($loan["date_issued"]); ?></td>
            <td><?php echo clean($loan["due_date"]); ?></td>
            <td>
                <?php echo $loan["date_returned"]
                    ? clean($loan["date_returned"])
                    : "Not returned"; ?>
              </td>

            <td>
                <?php if ($loan["date_returned"] === null): ?>
                    <form method="POST" action="return.php"
                          onsubmit="return confirm('Record this book as returned?');">
                        <input type="hidden" name="loan_id"
                               value="<?php echo clean($loan["loan_id"]); ?>">
                        <button type="submit">Return Book</button>
                    </form>
                <?php else: ?>
                    Returned
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>