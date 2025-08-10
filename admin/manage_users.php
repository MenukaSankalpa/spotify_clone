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
    background: #f8f9fa;
}
.sidebar {
    width: 220px;
    background: #2d3436;
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
    color: #dfe6e9;
    padding: 10px 0;
    text-decoration: none;
}
.sidebar a:hover {
    color: #00cec9;
}
.main-content {
    flex: 1;
    padding: 20px;
}
.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.table th, .table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
.table th {
    background: #0984e3;
    color: #fff;
}
.actions form {
    display: inline-block;
}
.actions button {
    background: #d63031;
    color: #fff;
    border: none;
    padding: 6px 10px;
    cursor: pointer;
    border-radius: 5px;
}
.actions button:hover {
    background: #ff7675;
}
.skip-form input[type="number"] {
    width: 60px;
    padding: 5px;
    text-align: center;
}
.skip-form button {
    background: #00b894;
    color: #fff;
    border: none;
    padding: 6px 10px;
    cursor: pointer;
    border-radius: 5px;
}
.skip-form button:hover {
    background: #55efc4;
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
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td class="actions">
                    <form method="POST">
                        <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                        <button type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
