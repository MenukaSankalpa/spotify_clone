<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$success = $error = "";

// Fetch user info for sidebar
$stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->num_rows > 0 ? $result->fetch_assoc() : ['username'=>'User','profile_pic'=>'default.png'];
$stmt->close();

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint'])) {
    $complaint = trim($_POST['complaint']);
    if ($complaint) {
        $stmt = $conn->prepare("INSERT INTO complaints (user_id, complaint, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $user_id, $complaint);
        if ($stmt->execute()) {
            $success = "✅ Complaint submitted successfully!";
        } else {
            $error = "⚠️ Failed to submit complaint. Try again.";
        }
        $stmt->close();
    }
}

// Fetch existing complaints with admin replies
$complaints = $conn->query("SELECT * FROM complaints WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Complaints</title>
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
.main {
    flex: 1;
    margin-left: 240px;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}
button.toggle-btn {
    background: #1db954;
    color: #fff;
    font-weight: bold;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}
button.toggle-btn:hover {
    background: #00cc66;
    transform: scale(1.03);
}
.card {
    background: #1e1e1e;
    border: 2px solid #1db954;
    border-radius: 12px;
    padding: 2rem;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 0 15px rgba(0, 221, 110, 0.3);
    animation: fadeIn 0.5s ease;
    display: none; /* hidden initially */
}
@keyframes fadeIn {
    from {opacity:0; transform: translateY(20px);}
    to {opacity:1; transform: translateY(0);}
}
.card h2 {
    color: #1db954;
    font-weight: bold;
    margin-bottom: 1.5rem;
    text-align: center;
}
.msg {
    padding: 10px;
    border-radius: 6px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1rem;
}
.success { background:#003300; color:#00ff99; }
.error { background:#330000; color:#ff6666; }
form textarea {
    width: 100%;
    height: 120px;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #1db954;
    background: #121212;
    color: #fff;
    font-weight: bold;
    resize: none;
    outline: none;
    transition: border 0.3s;
}
form textarea:focus {
    border-color: #00ff66;
}
form button {
    margin-top: 1rem;
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #1db954;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}
form button:hover {
    background: #00cc66;
    transform: scale(1.03);
}
.complaint-list {
    margin-top: 2rem;
    width: 100%;
    max-width: 500px;
}
.complaint-item {
    background: #2a2a2a;
    padding: 12px;
    border-radius: 8px;
    border-left: 4px solid #1db954;
    margin-bottom: 1rem;
}
.complaint-item span {
    font-weight: bold;
    color: #1db954;
}
.admin-reply {
    background: #111;
    border-left: 4px solid #00cc66;
    padding: 10px;
    margin-top: 0.5rem;
    border-radius: 6px;
}
.admin-reply span {
    color: #00cc66;
    font-weight: bold;
}
.admin-reply p {
    margin: 5px 0 0 0;
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main">
    <button class="toggle-btn" id="newComplaintBtn"><i class="fas fa-plus"></i> New Complaint</button>

    <div class="card" id="complaintForm">
        <h2>📩 Submit a Complaint</h2>

        <?php if ($success): ?>
            <div class="msg success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="msg error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <textarea name="complaint" placeholder="Write your complaint..." required></textarea>
            <button type="submit"><i class="fas fa-paper-plane"></i> Submit</button>
        </form>
    </div>

    <div class="complaint-list">
        <?php while($c = $complaints->fetch_assoc()): ?>
            <div class="complaint-item">
                <span>📝 <?php echo date('d M Y H:i', strtotime($c['created_at'])); ?></span>
                <p><?php echo htmlspecialchars($c['complaint']); ?></p>

                <?php if (!empty($c['reply'])): ?>
                    <div class="admin-reply">
                        <span>💬 Admin Reply: <?php echo date('d M Y H:i', strtotime($c['replied_at'])); ?></span>
                        <p><?php echo htmlspecialchars($c['reply']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
const newComplaintBtn = document.getElementById("newComplaintBtn");
const complaintForm = document.getElementById("complaintForm");

newComplaintBtn.addEventListener("click", () => {
    complaintForm.style.display = complaintForm.style.display === "block" ? "none" : "block";
    complaintForm.scrollIntoView({ behavior: "smooth" });
});
</script>
</body>
</html>
