<?php
session_start();

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #121212;
      color: #fff;
      display: flex;
      min-height: 100vh;
    }

    .main-content {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .dashboard-box {
      background: #1e1e1e;
      padding: 3rem 2rem;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.05);
      max-width: 600px;
      width: 100%;
      text-align: center;
    }

    h2 {
      margin-top: 0;
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #e50914;
    }

    p {
      font-size: 1.1rem;
      color: #ccc;
    }
  </style>
</head>
<body>

  <?php include 'sidebar.php'; ?>

  <div class="main-content">
    <div class="dashboard-box">
      <h2>Welcome Admin!</h2>
      <p>You are logged in as an administrator.</p>
    </div>
  </div>

</body>
</html>
