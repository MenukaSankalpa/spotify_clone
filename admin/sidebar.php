<!-- admin/sidebar.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="sidebar">
  <div class="sidebar-header">
    <i class="fa-brands fa-spotify logo-icon"></i>
    <span class="logo-text">SpotifyTunes</span>
  </div>
  
  <nav class="sidebar-nav">
    <a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="manage_songs.php"><i class="fas fa-music"></i> Manage Songs</a>
    <a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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

  .logo-icon {
    font-size: 1.8rem;
    color: #1db954;
    margin-right: 0.5rem;
  }

  .logo-text {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff;
  }

  .sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .sidebar-nav a {
    color: #ccc;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: background 0.2s ease, color 0.2s ease;
    font-size: 1rem;
  }

  .sidebar-nav a:hover {
    background-color: #1db954;
    color: #fff;
  }

  @media (max-width: 768px) {
    .sidebar {
      width: 100%;
      flex-direction: row;
      padding: 1rem;
      border-right: none;
      border-bottom: 1px solid #333;
    }

    .sidebar-header {
      display: none;
    }

    .sidebar-nav {
      flex-direction: row;
      justify-content: space-around;
      width: 100%;
    }

    .sidebar-nav a {
      font-size: 0.9rem;
      padding: 8px 10px;
      gap: 5px;
    }
  }
</style>
