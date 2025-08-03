<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - SpotifyTunes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #1e1e1e;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #1DB954;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: none;
            border-radius: 8px;
            background-color: #2b2b2b;
            color: white;
            font-size: 1rem;
        }

        input:focus {
            outline: 2px solid #1DB954;
        }

        button {
            background-color: #1DB954;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #17a94a;
        }

        p {
            margin-top: 15px;
            font-size: 0.95rem;
        }

        a {
            color: #1DB954;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="index.php">Login</a></p>
        </form>
    </div>
</body>
</html>
