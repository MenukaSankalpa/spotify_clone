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
    background: #f4f6f8;
}
.sidebar {
    width: 220px;
    background: #1e272e;
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
    color: #d2dae2;
    padding: 10px 0;
    text-decoration: none;
    transition: color 0.3s;
}
.sidebar a:hover {
    color: #00cec9;
}
.main-content {
    flex: 1;
    padding: 30px;
}
h1 {
    font-weight: 600;
    color: #2f3640;
    margin-bottom: 20px;
}
.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.table th, .table td {
    padding: 14px 16px;
    border-bottom: 1px solid #eee;
    text-align: left;
}
.table th {
    background: #0984e3;
    color: #fff;
    font-weight: 600;
}
.table tr:hover {
    background: #f1f3f5;
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
}
.btn-update {
    background: #00b894;
    color: #fff;
}
.btn-update:hover {
    background: #019474;
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
    border: 1px solid #ccc;
    border-radius: 6px;
    text-align: center;
    font-size: 14px;
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
                <td style="font-size: 12px; color:#555;"><?= htmlspecialchars($u['password']) ?></td>
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
