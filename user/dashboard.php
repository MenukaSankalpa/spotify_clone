<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT username, profile_pic, skip_count, last_skip_reset FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Reset skip count if 1 hour passed
if ($user['last_skip_reset'] && strtotime($user['last_skip_reset']) <= strtotime('-1 hour')) {
    $conn->query("UPDATE users SET skip_count = 0, last_skip_reset = NOW() WHERE id = $user_id");
    $user['skip_count'] = 0;
}

// Fetch all songs
$songs = $conn->query("SELECT * FROM songs ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #121212;
    color: #fff;
    display: flex;
    min-height: 100vh;
}

/* Main content aligned after sidebar */
.main {
    flex: 1;
    margin-left: 240px; /* match sidebar width */
    padding: 2rem;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Welcome message */
.welcome {
    font-size: 22px;
    font-weight: 600;
    color: #1db954;
    margin-bottom: 1.5rem;
    animation: fadeSlide 1s ease-out;
}
.welcome span { color: #fff; }
@keyframes fadeSlide {
    0% { opacity: 0; transform: translateY(-15px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Player styling */
.player {
    background: #1e1e1e;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.player audio {
    width: 100%;
    outline: none;
}
.player audio::-webkit-media-controls-enclosure {
    background-color: #1e1e1e;
}
.player button {
    background: #00cc66;
    border: none;
    color: #fff;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}
.player button:hover {
    background: #00b25c;
}

/* Songs list */
.songs-list {
    flex: 1;
    overflow-y: auto;
    background: #1e1e1e;
    padding: 1rem;
    border-radius: 10px;
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
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 1rem;
    border-radius: 6px;
}
.song-item button {
    margin-left: auto;
    background: #00cc66;
    border: none;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
}
.song-item button:hover {
    background: #00b25c;
}

.error-msg {
    background: #330000;
    color: #ff6666;
    padding: 8px;
    border-radius: 6px;
    text-align: center;
    margin-bottom: 1rem;
    display: none;
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="welcome">👋 Welcome, <span><?php echo htmlspecialchars($user['username']); ?></span></div>

    <div class="error-msg" id="errorMsg">⚠️ You can skip after 1 hour</div>

    <div class="player">
        <audio id="audioPlayer" controls controlsList="nodownload">
            <source src="" type="audio/mpeg">
        </audio>
        <button id="skipBtn">⏭ Skip</button>
    </div>

    <div class="songs-list" id="songList">
        <?php while($song = $songs->fetch_assoc()): ?>
        <div class="song-item">
            <img src="../uploads/covers/<?php echo htmlspecialchars($song['cover_image']); ?>" alt="Cover">
            <div><strong><?php echo htmlspecialchars($song['title']); ?></strong></div>
            <button onclick="playSong('../uploads/songs/<?php echo htmlspecialchars($song['file_path']); ?>')">▶ Play</button>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
const player = document.getElementById("audioPlayer");
const skipBtn = document.getElementById("skipBtn");
const errorMsg = document.getElementById("errorMsg");

let skipCount = <?php echo (int)$user['skip_count']; ?>;
const maxSkips = 6;

// Create a playlist array from the song items
const songItems = Array.from(document.querySelectorAll(".song-item"));
let currentSongIndex = -1;

function playSong(file, index) {
    player.src = file;
    player.play();
    currentSongIndex = index;
}

// Skip button logic
skipBtn.addEventListener("click", () => {
    if (skipCount < maxSkips) {
        skipCount++;
        fetch("update_skip.php"); // update skip count in DB
        playNextSong();
    } else {
        errorMsg.style.display = "block";
    }
});

// Play next song in playlist
function playNextSong() {
    currentSongIndex++;
    if (currentSongIndex >= songItems.length) {
        currentSongIndex = 0; // loop back to first song
    }
    const nextSongBtn = songItems[currentSongIndex].querySelector("button");
    const file = nextSongBtn.getAttribute("onclick").match(/'([^']+)'/)[1];
    playSong(file, currentSongIndex);
}

// Automatically play next song when current ends
player.addEventListener("ended", () => {
    playNextSong();
});

// Attach click listeners to song play buttons
songItems.forEach((item, index) => {
    const btn = item.querySelector("button");
    btn.addEventListener("click", () => {
        const file = btn.getAttribute("onclick").match(/'([^']+)'/)[1];
        playSong(file, index);
    });
});
</script>

</body>
</html>
