<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}
require 'db.php';

// Upload song
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['song'])) {
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $fileName = basename($_FILES['song']['name']);
    $targetDir = 'assets/audio/';
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['song']['tmp_name'], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO songs (title, artist, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $artist, $targetFile);
        $stmt->execute();
    }
}

// Delete song
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM songs WHERE id = $id");
}

// Fetch all songs
$songs = $conn->query("SELECT * FROM songs ORDER BY uploaded_at DESC");
include 'includes/header.php';
?>
<div class="container">
    <h2>Admin Panel</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Song Title" required>
        <input type="text" name="artist" placeholder="Artist">
        <input type="file" name="song" required>
        <button type="submit">Upload Song</button>
    </form>

    <h3>All Songs</h3>
    <table class="song-table">
        <tr><th>ID</th><th>Title</th><th>Artist</th><th>File</th><th>Action</th></tr>
        <?php while ($row = $songs->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['artist']) ?></td>
                <td><?= basename($row['file_path']) ?></td>
                <td><a class="delete-btn" href="?delete=<?= $row['id'] ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'includes/footer.php'; ?>