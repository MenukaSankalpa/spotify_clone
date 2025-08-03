<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - SpotifyTunes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: #1e1e1e;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            color: #1DB954;
            margin-bottom: 40px;
            font-size: 1.4rem;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 1rem;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2b2b2b;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .content h1 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #1DB954;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_songs.php">Manage Songs</a>
        <a href="manage_admins.php">Manage Admins</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, Admin!</h1>
        <p>Select an option from the menu.</p>
    </div>
</body>
</html>
