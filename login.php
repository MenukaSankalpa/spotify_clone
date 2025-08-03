<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    // Redirect based on role if already logged in
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {

        // Hardcoded admin login (optional)
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 0;  // Admin user ID (arbitrary)
            $_SESSION['username'] = 'admin';
            $_SESSION['role'] = 'admin';

            header("Location: admin_dashboard.php");
            exit();
        }

        // Check database for user login
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $user, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $user;
                $_SESSION['role'] = $role;

                if ($role === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in both fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login - Spotify Clone</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="login-container">
            <h2>Login</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" action="login.php" autocomplete="off">
                <input type="text" name="username" placeholder="Username" required autofocus />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit">Login</button>
            </form>
            <p style="text-align:center; margin-top: 10px;">
                Don't have an account? <a href="register.php" style="color: #1DB954;">Register</a>
            </p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
