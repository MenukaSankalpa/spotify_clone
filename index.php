<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: player.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpotifyTunes</title>
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

        .login-container {
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
    <main>
        <div class="login-container">
            <h2>LOGIN</h2>
            <form action="login.php" method="POST">
                <input type="text" name="username" id="username" placeholder="Username" required />
                <input type="password" name="password" id="password" placeholder="Password" required />
                <button type="submit">Login</button>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </main>
</body>
</html>
