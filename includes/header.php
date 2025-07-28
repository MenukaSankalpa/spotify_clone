<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenTunes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="navbar">
    <h1>GreenTunes</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>