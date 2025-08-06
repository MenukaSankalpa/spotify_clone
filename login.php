<?php
ob_start();
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        // Check admin table
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($admin_id, $admin_hashed_password);
            $stmt->fetch();

            // Use password_verify to check hashed password
            if (password_verify($password, $admin_hashed_password)) {
                $_SESSION['user_id'] = $admin_id;
                $_SESSION['is_admin'] = true;
                header('Location: admin/dashboard.php');
                exit();
            } else {
                $error = 'Invalid admin username or password.';
            }
            $stmt->close();
        } else {
            $stmt->close();
            // Check users table
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($user_id, $user_hashed_password);
                $stmt->fetch();

                if (password_verify($password, $user_hashed_password)) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['is_admin'] = false;
                    header('Location: user/dashboard.php');
                    exit();
                } else {
                    $error = 'Invalid username or password.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
            $stmt->close();
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SpotifyTunes - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php if ($error): ?>
      <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" autocomplete="off">
      <input type="text" name="username" placeholder="Username" required autofocus
        value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>

  <script>
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const errorMessage = document.querySelector('.error-message');

    function hideError() {
      if (errorMessage) {
        errorMessage.style.display = 'none';
      }
    }

    usernameInput.addEventListener('input', hideError);
    passwordInput.addEventListener('input', hideError);
  </script>
</body>
</html>
