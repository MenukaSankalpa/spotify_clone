<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>GreenTunes Player</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <h1>GreenTunes</h1>
        <a href="logout.php">Logout</a>
    </div>
    <div class="player">
        <h2>Now Playing</h2>
        <audio controls>
            <source src="assets/audio/sample.mp3" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>
    </div>
</body>
</html>
