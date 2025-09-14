<?php
// Fetch user info
if (!isset($user)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Determine profile image
$profileImage = $user['profile_pic'] && file_exists("../uploads/profiles/".$user['profile_pic']) 
                ? $user['profile_pic'] 
                : 'default.png';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="sidebar">
  <!-- Logo -->
  <div class="sidebar-header">
    <i class="fa-brands fa-spotify logo-icon"></i>
    <span class="logo-text">SpotifyTunes</span>
  </div>

  <!-- User Profile -->
  <div class="sidebar-profile">
    <img src="../uploads/profiles/<?php echo htmlspecialchars($profileImage); ?>" alt="Profile">
    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
  </div>

  <!-- Nav Links -->
  <nav class="sidebar-nav">
    <a href="profile.php"><i class="fas fa-user-circle"></i> <strong>Manage Profile</strong></a>
    <a href="dashboard.php"><i class="fas fa-music"></i> <strong>Song List</strong></a>
    <a href="complaints.php"><i class="fas fa-envelope"></i> <strong>Complaints</strong></a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <strong>Logout</strong></a>
  </nav>
</div>

<style>
.sidebar {
  width: 240px;
  background-color: #1a1a1a;
  padding: 2rem 1rem;
  display: flex;
  flex-direction: column;
  border-right: 1px solid #333;
  min-height: 100vh;
  position: sticky;
  top: 0;
}
.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
}
.logo-icon { font-size: 1.8rem; color: #1db954; margin-right: 0.5rem; }
.logo-text { font-size: 1.5rem; font-weight: bold; color: #fff; }
.sidebar-profile { text-align: center; margin-bottom: 2rem; }
.sidebar-profile img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #1db954; margin-bottom: 0.5rem; }
.sidebar-profile h3 { margin: 0; font-size: 1rem; color: #fff; font-weight: bold; }
.sidebar-nav { display: flex; flex-direction: column; gap: 1rem; }
.sidebar-nav a {
  color: #ccc; text-decoration: none; padding: 10px 15px;
  border-radius: 8px; display: flex; align-items: center; gap: 10px;
  transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
  font-size: 1rem; font-weight: bold;
}
.sidebar-nav a:hover { background-color: #1db954; color: #fff; transform: translateX(5px); }

@media (max-width: 768px) {
  .sidebar { width: 100%; flex-direction: row; align-items: center; padding: 1rem; border-right: none; border-bottom: 1px solid #333; }
  .sidebar-header { display: none; }
  .sidebar-profile { display: none; }
  .sidebar-nav { flex-direction: row; justify-content: space-around; width: 100%; }
  .sidebar-nav a { font-size: 0.9rem; padding: 8px 10px; gap: 5px; }
}
</style>
