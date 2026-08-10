<?php

session_start();

require_once "../config/database.php";

// User must be logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;
}

// Check blog ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: ../index.php");
    exit;
}

$blogId = (int) $_GET["id"];

// Delete only if this blog belongs to the logged-in user
$sql = "DELETE FROM blogPost
        WHERE id = ? AND user_id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $blogId,
    $_SESSION["user_id"]
]);

// Go back to dashboard
header("Location: ../dashboard/index.php");
exit;

?>