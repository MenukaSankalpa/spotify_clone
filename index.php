<?php
session_start();
require 'db.php'; // adjust path as needed

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        // Check user in DB here, simplified example:
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                header('Location: player.php');
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    } else {
        $error = 'Please fill in all fields.';
    }
}

if (isset($_SESSION['user_id'])) {
    header("Location: player.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>SpotifyTunes - Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
<style>
  /* Reset */
  *, *::before, *::after {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #1db954 0%, #191414 100%);
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .login-container {
    background: #121212;
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(29, 185, 84, 0.6);
    width: 100%;
    max-width: 400px;
    text-align: center;
  }
  h2 {
    margin-bottom: 2rem;
    font-weight: 600;
    font-size: 2rem;
    color: #1DB954;
  }
  form {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
  }
  input {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 8px;
    border: none;
    background: #282828;
    color: #fff;
    transition: background 0.3s ease;
  }
  input::placeholder {
    color: #b3b3b3;
  }
  input:focus {
    outline: 2px solid #1DB954;
    background: #333;
  }
  button {
    background: #1DB954;
    border: none;
    padding: 0.9rem;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  button:hover {
    background: #17a94a;
  }
  p {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: #b3b3b3;
  }
  a {
    color: #1DB954;
    text-decoration: none;
    font-weight: 600;
  }
  a:hover {
    text-decoration: underline;
  }
  /* Error message styling */
  .error-message {
    color: #ff4c4c;
    margin-bottom: 1rem;
    font-weight: 600;
    min-height: 1.2em; /* Reserve space so container doesn't jump */
  }
</style>
</head>
<body>
  <main>
    <div class="login-container">
      <h2>Login</h2>

      <div class="error-message">
        <?php echo htmlspecialchars($error); ?>
      </div>

      <form action="" method="POST" autocomplete="off">
        <input type="text" name="username" id="username" placeholder="Username" required autofocus
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
        <input type="password" name="password" id="password" placeholder="Password" required />
        <button type="submit">Login</button>
      </form>
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </main>
</body>
</html>
