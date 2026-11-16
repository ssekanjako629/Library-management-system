
<?php

// Display messages safely
function clean($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

// Check whether a user is logged in
function isLoggedIn() {
    return isset($_SESSION["user_id"]);
}

// Redirect to another page
function redirect($page) {
    header("Location: " . $page);
    exit();
}

?>
