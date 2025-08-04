<?php
session_start();
require 'db.php';

$error = '';

// Admin credentials
$admin_username = 'admin';
$admin_password_hash = password_hash('123', PASSWORD_DEFAULT); // hash for "123"

// Or you can pre-generate the hash of "123" and paste it here, for example:
// $admin_password_hash = '$2y$10$U6cM2gLrSYDYP18ty6Vj8u1uZ5Lo2lyDMN9PrlL2l31HXsj9ci5pu'; // hash of "123"

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        if ($username === $admin_username) {
            // Check admin password
            if (password_verify($password, $admin_password_hash)) {
                $_SESSION['user_id'] = 'admin';
                $_SESSION['is_admin'] = true;
                header('Location: admin_dashboard.php');
                exit();
            } else {
                $error = 'Invalid admin credentials.';
            }
        } else {
            // Normal user login via database
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['is_admin'] = false;
                    header('Location: player.php');
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

// If already logged in redirect accordingly
if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['is_admin'])) {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: player.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SpotifyTunes - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../spotify_clone/assets/css/style.css" />
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
