<?php
session_start();
require '../db.php';

// Check admin login
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

// Fetch complaints with user info
$complaints = $conn->query("
    SELECT c.id, c.user_id, c.complaint, c.reply, c.created_at, u.username
    FROM complaints c
    JOIN users u ON u.id = c.user_id
    ORDER BY c.created_at DESC
");

// Handle admin reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['complaint_id'])) {
    $reply = trim($_POST['reply']);
    $complaint_id = (int)$_POST['complaint_id'];

    if ($reply) {
        $stmt = $conn->prepare("UPDATE complaints SET reply = ? WHERE id = ?");
        $stmt->bind_param("si", $reply, $complaint_id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_complaints.php"); // Refresh page
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Complaints</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { margin:0; font-family:'Poppins',sans-serif; background:#121212; color:#fff; display:flex; }
.main { flex:1; margin-left:240px; padding:2rem; display:flex; flex-direction:column; align-items:center; }
.complaint-item { width:100%; max-width:600px; background:#1e1e1e; padding:1rem; border-radius:10px; margin-bottom:1rem; border-left:4px solid #1db954; }
.complaint-item h4 { margin:0 0 0.5rem 0; font-weight:bold; color:#1db954; }
.complaint-item p { margin:0.2rem 0; }
.reply-form { margin-top:1rem; display:flex; flex-direction:column; }
.reply-form textarea { width:100%; padding:8px; border-radius:6px; border:1px solid #1db954; background:#121212; color:#fff; font-weight:bold; resize:none; }
.reply-form button { margin-top:0.5rem; padding:8px; border:none; border-radius:6px; background:#00cc66; color:#fff; font-weight:bold; cursor:pointer; }
.reply-form button:hover { background:#00b25c; }
.reply-msg { margin-top:0.5rem; padding:8px; background:#003300; border-radius:6px; font-weight:bold; color:#00ff99; }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <h2>📩 User Complaints</h2>

    <?php while($c = $complaints->fetch_assoc()): ?>
        <div class="complaint-item">
            <h4>User: <?php echo htmlspecialchars($c['username']); ?> | <?php echo date('d M Y H:i', strtotime($c['created_at'])); ?></h4>
            <p><strong>Complaint:</strong> <?php echo htmlspecialchars($c['complaint']); ?></p>

            <?php if ($c['reply']): ?>
                <div class="reply-msg">📝 Reply: <?php echo htmlspecialchars($c['reply']); ?></div>
            <?php else: ?>
                <form method="post" class="reply-form">
                    <textarea name="reply" placeholder="Write a reply..." required></textarea>
                    <input type="hidden" name="complaint_id" value="<?php echo $c['id']; ?>">
                    <button type="submit"><i class="fas fa-paper-plane"></i> Reply</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
