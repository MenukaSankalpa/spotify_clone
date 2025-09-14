<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$success = $error = "";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    // Upload profile picture if provided
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/profiles/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;
        $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($ext, ["jpg","jpeg","png","gif"])) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                // Delete old profile pic
                if ($user['profile_pic'] && file_exists($target_dir . $user['profile_pic'])) {
                    unlink($target_dir . $user['profile_pic']);
                }
                $stmt = $conn->prepare("UPDATE users SET profile_pic = ?, username = ? WHERE id = ?");
                $stmt->bind_param("ssi", $file_name, $username, $user_id);
                $stmt->execute();
                $stmt->close();
                $success = "✅ Profile updated successfully!";
                $user['profile_pic'] = $file_name;
                $user['username'] = $username;
            } else {
                $error = "⚠️ Failed to upload image.";
            }
        } else {
            $error = "⚠️ Only JPG, PNG, and GIF allowed.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $stmt->close();
        $success = "✅ Username updated successfully!";
        $user['username'] = $username;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Profile</title>
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

/* Main content */
.main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

/* Card */
.card {
    background: #000000;
    border: 2px solid #1db954;
    border-radius: 12px;
    padding: 2.5rem;
    max-width: 450px;
    width: 100%;
    text-align: center;
    box-shadow: 0 0 20px rgba(0, 221, 110, 0.5);
    animation: fadeIn 0.6s ease;
}
@keyframes fadeIn {
    from {opacity:0; transform: translateY(20px);}
    to {opacity:1; transform: translateY(0);}
}

/* Title */
.card h2 {
    margin-bottom: 1.5rem;
    color: #1db954;
    font-weight: bold;
}

/* Messages */
.msg {
    text-align: center;
    margin-bottom: 1rem;
    padding: 10px;
    border-radius: 6px;
    font-weight: bold;
}
.success {background:#003300; color:#00ff99;}
.error {background:#330000; color:#ff6666;}

/* Current pic */
.current-pic {
    margin-bottom: 1.5rem;
}
.current-pic img {
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #1db954;
}

/* Form */
form .form-group {
    margin-bottom:1.2rem;
    text-align:left;
}
form label {
    font-weight:bold;
    display:block;
    margin-bottom:0.5rem;
    color:#00ff99;
}
form input[type="text"], form input[type="file"] {
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #1db954;
    background:#121212;
    color:#fff;
    outline:none;
    font-weight:bold;
}
form input[type="text"]:focus, form input[type="file"]:focus {
    border-color:#00ff66;
}

/* Button */
.btn {
    display:block;
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1db954;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
    transition: all 0.3s ease;
}
.btn:hover {
    background:#00cc66;
    transform: scale(1.03);
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main">
    <div class="card">
        <h2>👤 Manage Profile</h2>

        <?php if ($success): ?>
            <div class="msg success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="msg error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="current-pic">
            <img src="../uploads/profiles/<?php echo htmlspecialchars($user['profile_pic'] ?? 'default.png'); ?>" alt="Profile Picture">
        </div>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_pic" accept="image/*">
            </div>
            <button type="submit" class="btn">💾 Save Changes</button>
        </form>
    </div>
</div>
</body>
</html>
