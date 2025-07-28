<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;

            // Redirect admin
            if ($db_username === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: player.php");
            }
            exit();
        }
    }

    // If login fails
    echo "<script>alert('Invalid credentials'); window.location.href='index.php';</script>";
}
?>
