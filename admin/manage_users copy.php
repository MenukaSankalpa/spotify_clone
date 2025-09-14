<?php
session_start();
require '../db.php';

// Check admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Handle delete
if (isset($_POST['delete_id'])) {
    $delete_id = (int) $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Handle skip limit update
if (isset($_POST['user_id'], $_POST['skip_limit'])) {
    $user_id = (int) $_POST['user_id'];
    $skip_limit = (int) $_POST['skip_limit'];
    $stmt = $conn->prepare("UPDATE users SET skip_limit = ? WHERE id = ?");
    $stmt->bind_param('ii', $skip_limit, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch users
$result = $conn->query("SELECT id, username, password, skip_limit FROM users ORDER BY id ASC");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    display: flex;
    margin: 0;
    background: linear-gradient(135deg, #1db954 0%, #191414 100%);
}
.sidebar {
    width: 220px;
    background: #000;
    color: #fff;
    padding: 20px;
    height: 100vh;
}
.sidebar h2 {
    font-size: 20px;
    margin-bottom: 20px;
}
.sidebar a {
    display: block;
    color: #dcdcdc;
    padding: 10px 0;
    text-decoration: none;
    transition: color 0.3s;
}
.sidebar a:hover {
    color: #1db954;
}
.main-content {
    flex: 1;
    padding: 30px;
}
h1 {
    font-weight: 600;
    color: #fff; /* White heading for contrast */
    margin-bottom: 20px;
}
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: rgba(255, 255, 255, 0.92); /* Slight transparency */
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.table th {
    background: #161616ff; /* Deep Spotify green */
    color: #fff;
    font-weight: 600;
    padding: 14px 16px;
    text-align: left;
    font-size: 15px;
}

.table td {
    padding: 14px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    color: #000;
    font-size: 14px;
}

.table tr:last-child td {
    border-bottom: none;
}

.table tr:hover {
    background: rgba(29, 185, 84, 0.08); /* Light green hover */
    transition: background 0.3s ease;
}

.actions form {
    display: inline-block;
}
button {
    font-size: 14px;
    padding: 6px 12px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    transition: background 0.3s;
    font-weight: 500;
}
.btn-update {
    background: #1db954;
    color: #fff;
}
.btn-update:hover {
    background: #17a44d;
}
.btn-delete {
    background: #d63031;
    color: #fff;
}
.btn-delete:hover {
    background: #ff4d4d;
}
.skip-form {
    display: flex;
    gap: 6px;
    align-items: center;
}
.skip-form input[type="number"] {
    width: 60px;
    padding: 6px;
    border: 1px solid #0b9c1eff;
    border-radius: 6px;
    text-align: center;
    font-size: 14px;
    color: #ffffffff;
}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h1>Manage Users</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password (hashed)</th>
                <th>Skip Limit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td style="font-size: 12px;"><?= htmlspecialchars($u['password']) ?></td>
                <td>
                    <form method="POST" class="skip-form">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <input type="number" name="skip_limit" min="0" value="<?= $u['skip_limit'] ?>">
                        <button type="submit" class="btn-update">Update</button>
                    </form>
                </td>
                <td class="actions">
                    <form method="POST" onsubmit="return confirm('Delete this user?')">
                        <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
