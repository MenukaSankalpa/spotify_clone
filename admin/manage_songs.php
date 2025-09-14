<?php 
session_start(); 
require '../db.php';  

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {     
    header('Location: ../login.php');     
    exit(); 
}  

// Handle song upload
$uploadMessage = ''; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['song_file']) && isset($_FILES['cover_image'])) {     
    $title = trim($_POST['title']);     
    $songFile = $_FILES['song_file'];     
    $coverImage = $_FILES['cover_image'];      

    if ($title && $songFile['error'] === 0 && $coverImage['error'] === 0) {         
        $songExt = pathinfo($songFile['name'], PATHINFO_EXTENSION);         
        $coverExt = pathinfo($coverImage['name'], PATHINFO_EXTENSION);          

        $songName = uniqid() . '.' . $songExt;         
        $coverName = uniqid() . '.' . $coverExt;          

        $songPath = '../uploads/songs/' . $songName;         
        $coverPath = '../uploads/covers/' . $coverName;          

        move_uploaded_file($songFile['tmp_name'], $songPath);         
        move_uploaded_file($coverImage['tmp_name'], $coverPath);          

        $stmt = $conn->prepare("INSERT INTO songs (title, file_path, cover_image) VALUES (?, ?, ?)");         
        $stmt->bind_param("sss", $title, $songName, $coverName);         
        $stmt->execute();         
        $stmt->close();          

        $uploadMessage = '<div class="upload-success">✅ Song uploaded successfully!</div>';     
    } else {         
        $uploadMessage = '<div class="upload-success" style="background:#330000; border-left-color:red;">❌ Upload failed. Please try again.</div>';     
    } 
}  

// Handle delete
if (isset($_GET['delete'])) {     
    $id = (int)$_GET['delete'];     
    $result = $conn->query("SELECT file_path, cover_image FROM songs WHERE id = $id");     
    $song = $result->fetch_assoc();      

    if ($song) {         
        if (file_exists("../uploads/songs/" . $song['file_path'])) {             
            unlink("../uploads/songs/" . $song['file_path']);         
        }         
        if (file_exists("../uploads/covers/" . $song['cover_image'])) {             
            unlink("../uploads/covers/" . $song['cover_image']);         
        }         
        $conn->query("DELETE FROM songs WHERE id = $id");     
    }     
    header("Location: manage_songs.php");     
    exit(); 
}  

// Fetch songs
$songs = $conn->query("SELECT * FROM songs ORDER BY created_at DESC"); 
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>   
  <meta charset="UTF-8">   
  <title>Manage Songs</title>   
  <link rel="stylesheet" href="../assets/css/style.css">   
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />   
  <style>     
    body {       
      margin: 0;       
      font-family: 'Poppins', sans-serif;       
      background: linear-gradient(135deg, #1db954 0%, #191414 100%);       
      color: #fff;       
      display: flex;     
    }     

    /* Sidebar stays fixed */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px; 
      background: #111;
      z-index: 100;
    }

    .main-content {       
      margin-left: 220px; /* same as sidebar width */
      flex: 1;       
      padding: 2rem;     
    }     

    /* Tab buttons */
    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 1rem;
    }
    .tabs button {
      background: #00cc66;
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
      transition: background 0.3s ease;
    }
    .tabs button:hover {
      background: #00b25c;
    }
    .tabs button.active {
      background: #00994d;
    }

    /* Form */
    .form-box {       
      max-width: 500px;       
      margin: 0 auto 2rem;       
      background: #1b1b1b;       
      padding: 1.5rem;       
      border-radius: 12px;       
      box-shadow: 0 0 15px rgba(0, 255, 0, 0.1);     
    }     
    .form-box h2 {       
      color: #66ff66;       
      margin-bottom: 1.2rem;       
      font-size: 20px;       
      text-align: center;     
    }     
    .form-group { margin-bottom: 1rem; }     
    .form-group label {       
      display: block;       
      color: #ccc;       
      margin-bottom: 0.5rem;       
      font-size: 14px;     
    }     
    .form-box input[type="text"],     
    .form-box input[type="file"] {       
      width: 100%;       
      background: #2a2a2a;       
      color: #fff;       
      border: 1px solid #333;       
      padding: 10px;       
      border-radius: 6px;       
      font-size: 14px;       
      transition: border 0.2s ease;     
    }     
    .form-box input:focus {       
      border: 1px solid #66ff66;       
      outline: none;     
    }     
    .form-box button {       
      background: #00cc66;       
      color: white;       
      border: none;       
      padding: 10px 16px;       
      border-radius: 6px;       
      cursor: pointer;       
      font-size: 15px;       
      width: 100%;       
      transition: background 0.3s ease;     
    }     
    .form-box button:hover { background: #00b25c; }     
    .upload-success {       
      margin-top: 1rem;       
      background: #003300;       
      padding: 1rem;       
      border-left: 4px solid #00ff00;       
      font-size: 14px;       
      border-radius: 6px;     
    }     

    /* Songs list */
    .songs-list {       
      background: #1e1e1e;       
      padding: 2rem;       
      border-radius: 10px;       
      height: 70vh;       
      overflow-y: auto;     
    }     
    .song-item {       
      display: flex;       
      align-items: center;       
      margin-bottom: 1rem;       
      background: #2a2a2a;       
      padding: 1rem;       
      border-radius: 8px;     
    }     
    .song-item img {       
      width: 60px;       
      height: 60px;       
      object-fit: cover;       
      margin-right: 1rem;       
      border-radius: 5px;     
    }     
    .song-item strong {       
      font-size: 16px;       
      color: #fff;     
    }     
    .song-item audio {       
      margin-left: auto;       
      margin-right: 1rem;     
    }     
    .song-item a {       
      color: #66ff66;       
      margin-left: 10px;       
      text-decoration: none;       
      font-size: 18px;     
    }     
    .song-item a:hover { color: #00ff99; }   
  </style> 
</head> 
<body>  

<?php include 'sidebar.php'; ?>  

<div class="main-content">    

  <!-- Tab buttons -->
  <div class="tabs">
    <button id="tabAdd" class="active">➕ Add Song</button>
    <button id="tabView">🎵 View Songs</button>
  </div>

  <!-- Add Song Form -->
  <div id="addSection" class="form-box">     
    <h2>Add New Song</h2>     
    <form method="POST" enctype="multipart/form-data">       
      <div class="form-group">         
        <label for="title">🎵 Title</label>         
        <input type="text" name="title" id="title" placeholder="e.g. My Cool Song" required>       
      </div>        
      <div class="form-group">         
        <label for="song_file">📁 MP3 File</label>         
        <input type="file" name="song_file" id="song_file" accept=".mp3" required>       
      </div>        
      <div class="form-group">         
        <label for="cover_image">🖼️ Cover Image</label>         
        <input type="file" name="cover_image" id="cover_image" accept="image/*" required>       
      </div>        
      <button type="submit"><i class="fas fa-upload"></i> Upload</button>     
    </form>     
    <?= $uploadMessage ?>   
  </div>    

  <!-- Songs List -->
  <div id="viewSection" class="songs-list" style="display:none;">     
    <h2>All Songs</h2>     
    <?php while($song = $songs->fetch_assoc()): ?>       
      <div class="song-item">         
        <img src="../uploads/covers/<?php echo htmlspecialchars($song['cover_image']); ?>" alt="Cover">         
        <div><strong><?php echo htmlspecialchars($song['title']); ?></strong></div>         
        <audio controls>           
          <source src="../uploads/songs/<?php echo htmlspecialchars($song['file_path']); ?>" type="audio/mpeg">           
        </audio>         
        <a href="edit_song.php?id=<?php echo $song['id']; ?>"><i class="fas fa-edit"></i></a>         
        <a href="?delete=<?php echo $song['id']; ?>" onclick="return confirm('Are you sure to delete this song?')">           
          <i class="fas fa-trash"></i>         
        </a>       
      </div>     
    <?php endwhile; ?>   
  </div>  
</div> 

<script>
const tabAdd = document.getElementById("tabAdd");
const tabView = document.getElementById("tabView");
const addSection = document.getElementById("addSection");
const viewSection = document.getElementById("viewSection");

tabAdd.addEventListener("click", () => {
  tabAdd.classList.add("active");
  tabView.classList.remove("active");
  addSection.style.display = "block";
  viewSection.style.display = "none";
});

tabView.addEventListener("click", () => {
  tabView.classList.add("active");
  tabAdd.classList.remove("active");
  addSection.style.display = "none";
  viewSection.style.display = "block";
});
</script>

</body> 
</html>
