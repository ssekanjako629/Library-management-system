
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $member_number = trim($_POST["member_number"]);
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $status = $_POST["status"];
    $date_joined = date("Y-m-d");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!in_array($status, ["active", "suspended"], true)) {
        $error = "Please select a valid membership status.";
    } else {
        try {
            $sql = "INSERT INTO members
                    (member_number, full_name, email, phone, status, date_joined)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $member_number,
                $full_name,
                $email,
                $phone ?: null,
                $status,
                $date_joined
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not register the member. Check that the member number and email are not already used.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Register New Member</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Member Number:</label><br>
        <input type="text" name="member_number" required><br><br>

        <label>Full Name:</label><br>
        <input type="text" name="full_name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone"><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
        </select><br><br>

        <button type="submit">Register Member</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
