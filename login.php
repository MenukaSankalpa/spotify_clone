<?php
require 'db.php';
session_start();
$username = $_POST['username'];
$password = $_POST['password'];
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $hashedPassword);
if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
    $_SESSION['user_id'] = $id;
    header("Location: player.php");
} else {
    echo "<script>alert('Invalid credentials'); window.location.href='index.php';</script>";
}
?>