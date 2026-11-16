<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $member_id = filter_input(INPUT_POST, "member_id", FILTER_VALIDATE_INT);
    $book_id = filter_input(INPUT_POST, "book_id", FILTER_VALIDATE_INT);

    if (!$member_id || !$book_id) {
        $error = "Please select a valid member and book.";
    } else {
        try {
            $conn->beginTransaction();

            // Check the member's status
            $stmt = $conn->prepare(
                "SELECT status FROM members WHERE member_id = ? FOR UPDATE"
            );
            $stmt->execute([$member_id]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$member) {
                throw new Exception("Member not found.");
            }

            if ($member["status"] !== "active") {
                throw new Exception("This member is suspended and cannot borrow books.");
            }

            // Check book availability
            $stmt = $conn->prepare(
                "SELECT available_copies FROM books WHERE book_id = ? FOR UPDATE"
            );
            $stmt->execute([$book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                throw new Exception("Book not found.");
            }

            if ($book["available_copies"] < 1) {
                throw new Exception("This book is currently unavailable.");
            }

            // Prevent the same member borrowing the same book twice
            $stmt = $conn->prepare(
                "SELECT COUNT(*) FROM loans
                 WHERE member_id = ? AND book_id = ? AND date_returned IS NULL"
            );
            $stmt->execute([$member_id, $book_id]);

            if ($stmt->fetchColumn() > 0) {
                throw new Exception("This member has already borrowed this book.");
            }

            // Create the loan (14-day borrowing period)
            $stmt = $conn->prepare(
                "INSERT INTO loans (member_id, book_id, date_issued, due_date)
                 VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY))"
            );
            $stmt->execute([$member_id, $book_id]);

            // Reduce available copies
            $stmt = $conn->prepare(
                "UPDATE books SET available_copies = available_copies - 1
                 WHERE book_id = ?"
            );
            $stmt->execute([$book_id]);

            $conn->commit();

            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $error = $e->getMessage();
        }
    }
}

// Load active members
$members = $conn->query(
    "SELECT member_id, full_name, member_number
     FROM members WHERE status = 'active'
     ORDER BY full_name"
)->fetchAll(PDO::FETCH_ASSOC);

// Load available books
$books = $conn->query(
    "SELECT book_id, title, available_copies
     FROM books WHERE available_copies > 0
     ORDER BY title"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Issue a Book</h1>

<?php if ($error): ?>
    <p style="color:red;">
        <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Select Member:</label>
    <select name="member_id" required>
        <option value="">Choose a member</option>

        <?php foreach ($members as $member): ?>
            <option value="<?php echo $member["member_id"]; ?>">
                <?php echo htmlspecialchars(
                    $member["full_name"] . " (" . $member["member_number"] . ")"
                ); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Select Book:</label>
    <select name="book_id" required>
        <option value="">Choose a book</option>

        <?php foreach ($books as $book): ?>
            <option value="<?php echo $book["book_id"]; ?>">
                <?php echo htmlspecialchars(
                    $book["title"] . " - " . $book["available_copies"] . " available"
                ); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <button type="submit">Issue Book</button>

</form>

<p><a href="index.php">Back to Loans</a></p>