
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";
require_once "../includes/functions.php";

$stmt = $conn->query("SELECT * FROM members ORDER BY full_name");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Members Management</h1>

    <p><a href="add.php">Register New Member</a></p>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Member ID</th>
            <th>Member Number</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Date Joined</th>
        </tr>

        <?php foreach ($members as $member): ?>
        <tr>
            <td><?php echo clean($member["member_id"]); ?></td>
            <td><?php echo clean($member["member_number"]); ?></td>
            <td><?php echo clean($member["full_name"]); ?></td>
            <td><?php echo clean($member["email"]); ?></td>
            <td><?php echo clean($member["phone"]); ?></td>
            <td><?php echo clean($member["status"]); ?></td>
            <td><?php echo clean($member["date_joined"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include "../includes/footer.php"; ?>
