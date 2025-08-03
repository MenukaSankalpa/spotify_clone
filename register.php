<?php
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password_raw = $_POST['password'];
    
    if ($username === '' || $password_raw === '') {
        $error = "Please fill in all fields.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Username already taken, please choose another.";
        } else {
            // Insert new user
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);
            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Error occurred while registering. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Register - SpotifyTunes</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
<style>
  /* Reset & base */
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
  .container {
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
  .error {
    background: #ff4c4c;
    color: #fff;
    padding: 0.8rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 600;
  }
</style>
</head>
<body>
  <div class="container">
    <h2>Create Account</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" autocomplete="off">
      <input type="text" name="username" placeholder="Username" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login</a></p>
  </div>
</body>
</html>
