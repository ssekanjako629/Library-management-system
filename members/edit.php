
<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$member_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$member_id) {
    die("Invalid member ID.");
}

$stmt = $conn->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$member_id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    die("Member not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $member_number = trim($_POST["member_number"]);
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $status = $_POST["status"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!in_array($status, ["active", "suspended"], true)) {
        $error = "Please select a valid membership status.";
    } else {
        try {
            $sql = "UPDATE members SET
                    member_number = ?, full_name = ?, email = ?,
                    phone = ?, status = ?
                    WHERE member_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $member_number,
                $full_name,
                $email,
                $phone ?: null,
                $status,
                $member_id
            ]);

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $error = "Could not update the member. Check that the member number and email are not already used.";
        }
    }
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/navbar.php"; ?>

<main class="container">
    <h1>Edit Member</h1>

    <?php if ($error): ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Member Number:</label><br>
        <input type="text" name="member_number"
               value="<?php echo htmlspecialchars($member["member_number"]); ?>"
               required><br><br>

        <label>Full Name:</label><br>
        <input type="text" name="full_name"
               value="<?php echo htmlspecialchars($member["full_name"]); ?>"
               required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email"
               value="<?php echo htmlspecialchars($member["email"]); ?>"
               required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone"
               value="<?php echo htmlspecialchars($member["phone"] ?? ""); ?>"><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="active"
                <?php echo $member["status"] === "active" ? "selected" : ""; ?>>
                Active
            </option>

            <option value="suspended"
                <?php echo $member["status"] === "suspended" ? "selected" : ""; ?>>
                Suspended
            </option>
        </select><br><br>

        <button type="submit">Update Member</button>
        <a href="index.php">Cancel</a>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
