<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Spotify Clone</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <div class="navbar">
            <h1><a href="<?php echo $_SESSION['role'] === 'admin' ? '/admin_dashboard.php' : '/dashboard.php'; ?>">Spotify Clone</a></h1>
            <nav>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="/manage_users.php">Users</a>
                        <a href="/manage_songs.php">Songs</a>
                        <a href="/gdpr/anonymize.php">Anonymize</a>
                    <?php else: ?>
                        <a href="/dashboard.php">Dashboard</a>
                        <a href="/search.php">Search</a>
                        <a href="/play.php">Player</a>
                        <a href="/gdpr/consent.php">Consent</a>
                    <?php endif; ?>
                    <a href="/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/login.php">Login</a>
                    <a href="/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
